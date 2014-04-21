<?php

/*
 * This file is part of the Block Relocation Problem package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\BRP\Solution\Dumper;

use Symfony\Component\Console\Output\OutputInterface;
use Xabbuh\BRP\Configuration\Dumper\ConsoleDumper as ConfigurationDumper;
use Xabbuh\BRP\Solution\SolutionInterface;

/**
 * Dump a configuration to a given output stream.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class ConsoleDumper implements DumperInterface
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
    public function dump(SolutionInterface $solution)
    {
        $dumper = new ConfigurationDumper($this->output);

        for ($step = 0; $step < count($solution); $step++) {
            $this->output->writeln('');
            $this->output->writeln($solution->getSolutionStep($step)->getDescription().':');
            $this->output->writeln('');
            $dumper->dump($solution->getSolutionStep($step)->getConfiguration());
        }

        $this->output->writeln('');
        $this->output->writeln('<info>The solution has '.count($solution).' steps');
    }
}
