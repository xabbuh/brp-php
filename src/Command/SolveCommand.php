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
use Xabbuh\BRP\Algorithm\AlgorithmFactory;
use Xabbuh\BRP\Configuration\Loader\FileLoader;
use Xabbuh\BRP\Configuration\Parser\JsonParser;
use Xabbuh\BRP\Solution\Writer\ConsoleWriter;

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
            ->addOption('algorithm', 'a', InputOption::VALUE_REQUIRED, 'Name of the algorithm to use')
            ->addArgument('file', InputArgument::REQUIRED, 'The file containing the brp configuration');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $algorithmName = $input->getOption('algorithm');

        if (null === $algorithmName) {
            $output->writeln('<error>You have to specify the algorithm</error>');

            return;
        }

        try {
            $algorithmFactory = new AlgorithmFactory();
            $algorithm = $algorithmFactory->getAlgorithm($algorithmName);
        } catch (\InvalidArgumentException $e) {
            $output->writeln('<error>Algorithm '.$algorithmName. ' does not exist</error>');

            return;
        }

        $resource = realpath($input->getArgument('file'));

        if (false === $resource) {
            $output->writeln('<error>File does not exist.</error>');

            return;
        }

        $loader = new FileLoader(new JsonParser(), new \SplFileInfo($resource));
        $solution = $algorithm->solve($loader->load());
        $writer = new ConsoleWriter($output);
        $writer->write($solution);
    }
}
