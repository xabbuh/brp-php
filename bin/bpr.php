#!/usr/bin/env php
<?php
require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\Console\Application;
use Xabbuh\BRP\Command\ShowConfigurationCommand;
use Xabbuh\BRP\Command\SolveCommand;

$application = new Application();
$application->addCommands(array(
    new ShowConfigurationCommand(),
    new SolveCommand(),
));
$application->run();
