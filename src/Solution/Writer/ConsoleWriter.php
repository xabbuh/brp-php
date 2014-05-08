<?php

/*
 * This file is part of the Block Relocation Problem package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\BRP\Solution\Writer;

use Symfony\Component\Console\Output\OutputInterface;
use Xabbuh\BRP\Configuration\Writer\ConsoleWriter as ConfigurationWriter;
use Xabbuh\BRP\Solution\SolutionInterface;

/**
 * Write a configuration to a given output stream.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class ConsoleWriter implements WriterInterface
{
    /**
     * @var OutputInterface
     */
    protected $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * {@inheritDoc}
     */
    public function write(SolutionInterface $solution)
    {
        $writer = new ConfigurationWriter($this->output);

        for ($step = 0; $step < count($solution); $step++) {
            $this->output->writeln('');
            $this->output->writeln($solution->getSolutionStep($step)->getDescription().':');
            $this->output->writeln('');
            $writer->write($solution->getSolutionStep($step)->getConfiguration());
        }

        $this->output->writeln('');
        $this->output->writeln('<info>The solution has '.count($solution).' steps');
    }
}
