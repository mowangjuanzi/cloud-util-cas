<?php

namespace App\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SslCommand extends Command
{
    protected function configure(): void
    {
        $this->setName('ssl')
            ->setDescription('阿里云SSL证书服务')
            ->setHelp('阿里云SSL证书服务1')
            ->setAliases(['ssl']);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {


        return Command::SUCCESS;
    }
}
