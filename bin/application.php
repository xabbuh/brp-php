#!/usr/bin/env php
<?php
require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\Console\Application;
use Xabbuh\BRP\Command\ShowConfigurationCommand;

$application = new Application();
$application->addCommands(array(new ShowConfigurationCommand()));
$application->run();
