<?php

/*
 * This file is part of the Block Relocation Problem package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\BRP\Configuration\Dumper;

use Symfony\Component\Console\Output\OutputInterface;
use Xabbuh\BRP\Configuration\ConfigurationInterface;

/**
 * Dump a configuration to a given output stream.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class ConsoleDumper implements DumperInterface
{
    protected $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * {@inheritDoc}
     */
    public function dump(ConfigurationInterface $configuration)
    {
        $maxHeight = $this->getHighestStackHeight($configuration);

        for ($i = $maxHeight - 1; $i >= 0; $i--) {
            $this->output->write(' ');

            for ($stack = 0; $stack < $configuration->getStackCount(); $stack++) {
                if ($configuration->getHeight($stack) > $i) {
                    $this->output->write($configuration->getContainer($stack, $i));
                } else {
                    $this->output->write(' ');
                }

                $this->output->write(' ');
            }

            $this->output->writeln('');
        }

        $this->output->writeln(str_repeat('-', $configuration->getStackCount() * 2 + 1));

        for ($stack = 0; $stack < $configuration->getStackCount(); $stack++) {
            $this->output->write(' '.$stack);
        }

        $this->output->writeln(' ');
    }

    /**
     * Calculates the max height over all stacks.
     *
     * @param ConfigurationInterface $configuration The container configuration
     *
     * @return int The max stack height
     */
    private function getHighestStackHeight(ConfigurationInterface $configuration)
    {
        $maxHeight = 0;

        for ($stack = 0; $stack < $configuration->getStackCount(); $stack++) {
            if ($configuration->getHeight($stack) > $maxHeight) {
                $maxHeight = $configuration->getHeight($stack);
            }
        }

        return $maxHeight;
    }
}
