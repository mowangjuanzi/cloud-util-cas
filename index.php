<?php

use App\Console\Commands\SslCommand;
use Symfony\Component\Console\Application;

require_once __DIR__ . '/vendor/autoload.php';

// load env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Symfony Console
$application = new Application();
$application->add(new SslCommand());
$application->run();
