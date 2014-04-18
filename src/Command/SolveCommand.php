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
use Xabbuh\BRP\Algorithm\LaAlgorithm;
use Xabbuh\BRP\Configuration\Loader\LoaderFactory;
use Xabbuh\BRP\Solution\Dumper\ConsoleDumper;

class SolveCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('brp:configuration:solve')
            ->setDescription('Solves a block relocation problem and dumps the solution')
            ->addArgument('file', InputArgument::REQUIRED, 'The file containing the bpr configuration');
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

        $algorithm = new LaAlgorithm();
        $solution = $algorithm->solve($loader->load($resource));
        $dumper = new ConsoleDumper($output);
        $dumper->dump($solution);
    }
}
