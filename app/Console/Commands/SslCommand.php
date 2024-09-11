<?php

namespace App\Console\Commands;

use AlibabaCloud\SDK\Cas\V20200407\Cas;
use AlibabaCloud\SDK\Cas\V20200407\Models\GetUserCertificateDetailRequest;
use AlibabaCloud\SDK\Cas\V20200407\Models\ListUserCertificateOrderRequest;
use App\Libraries\Env;
use Darabonba\OpenApi\Models\Config;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SslCommand extends Command
{
    protected function configure(): void
    {
        $this->setName('ssl')
            ->setDescription('阿里云SSL证书服务')
            ->setHelp('阿里云SSL证书服务1')
            ->setAliases(['ssl'])
            ->addArgument('host', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $host = $input->getArgument('host');

        $aliKey = Env::get('ALI_KEY');
        $aliSecret = Env::get('ALI_SECRET');

        $config = new Config([
            'accessKeyId' => $aliKey,
            'accessKeySecret' => $aliSecret,
            'endpoint' => 'cas.aliyuncs.com',
        ]);

        $list = $this->getList($config, $host);
        if ($list) {
            $detail = $this->getDetail($config, $list[0]['CertificateId'] ?? 0);

            file_put_contents("/etc/nginx/ssls/{$host}-{$detail['id']}.key", $detail['Key']);
        }

        return Command::SUCCESS;
    }

    /**
     * 获取指定域名的证书列表
     */
    protected function getList(Config $config, string $host): array
    {
        $client = new Cas($config);

        $request = new ListUserCertificateOrderRequest([
            'keyword' => 'blueskyrescue.org.cn',
            'orderType' => 'CERT',
            'status' => 'ISSUED',
        ]);

        $resp = $client->ListUserCertificateOrder($request);
        $data = (array)($resp->body->toMap()['CertificateOrderList'] ?? []);
        $data = array_filter($data, fn(array $item) => in_array($host, explode(',', $item['Sans'])));
        return array_values($data);
    }

    /**
     * 获取证书详情
     */
    protected function getDetail(Config $config, int $certificateId): array
    {
        $client = new Cas($config);

        $request = new GetUserCertificateDetailRequest([
            'certId' => $certificateId,
        ]);

        $resp = $client->GetUserCertificateDetail($request);
        return ($resp->body->toMap() ?: []);
    }
}
