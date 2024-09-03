<?php

use App\Console\Commands\SslCommand;
use Symfony\Component\Console\Application;

require_once __DIR__ . '/vendor/autoload.php';

$application = new Application();

$application->add(new SslCommand());

$application->run();
