<?php

/*
 * This file is part of the Block Relocation Problem package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\BRP\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Xabbuh\BRP\Configuration\Dumper\ConsoleDumper;
use Xabbuh\BRP\Configuration\Loader\LoaderFactory;

/**
 * Command to show BRP configurations on the console.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class ShowConfigurationCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('brp:configuration:show')
            ->setDescription('Loads a configuration and dumps it to the console')
            ->addArgument('file', InputArgument::REQUIRED, 'The file to load the configuration from');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $resource = realpath($input->getArgument('file'));

        if (false === $resource) {
            $output->writeln('<error>File does not exist.</error>');

            return;
        }

        $loaderFactory = new LoaderFactory();
        $loader = $loaderFactory->getLoader($resource);

        if (null === $loader) {
            $output->writeln('No loader found for resource '.$resource);

            return;
        }

        $dumper = new ConsoleDumper($output);
        $dumper->dump($loader->load($resource));
    }
}
