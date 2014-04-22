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
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Xabbuh\BRP\Algorithm\LanAlgorithm;
use Xabbuh\BRP\Configuration\Loader\LoaderFactory;
use Xabbuh\BRP\Solution\Dumper\ConsoleDumper;

/**
 * Command to solve a block relocation problem using the LA-N algorithm.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class LanSolveCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('brp:configuration:lan-solve')
            ->setDescription('Solves a block relocation problem using the LA-N algorithm and dumps the solution')
            ->addOption('look-ahead', null, InputOption::VALUE_REQUIRED, 'Number of steps to look ahead')
            ->addArgument('file', InputArgument::REQUIRED, 'The file containing the brp configuration');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $lookAhead = $input->getOption('look-ahead');

        if (null === $lookAhead) {
            $output->writeln('<error>You have to specify the look ahead</error>');

            return;
        }

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

        $algorithm = new LanAlgorithm($lookAhead);
        $solution = $algorithm->solve($loader->load($resource));
        $dumper = new ConsoleDumper($output);
        $dumper->dump($solution);
    }
}
