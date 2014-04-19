#!/usr/bin/env php
<?php
foreach (array(__DIR__.'/../vendor/autoload.php', __DIR__.'/../../../autoload.php') as $autoLoadFile) {
    if (file_exists($autoLoadFile)) {
        require_once $autoLoadFile;
    }
}

use Symfony\Component\Console\Application;
use Xabbuh\BRP\Command\ShowConfigurationCommand;
use Xabbuh\BRP\Command\SolveCommand;

$application = new Application();
$application->addCommands(array(
    new ShowConfigurationCommand(),
    new SolveCommand(),
));
$application->run();
