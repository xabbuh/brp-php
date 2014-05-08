<?php

/*
 * This file is part of the Block Relocation Problem package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\BRP\Configuration\Writer;

use Symfony\Component\Console\Output\OutputInterface;
use Xabbuh\BRP\Configuration\ConfigurationInterface;
use Xabbuh\BRP\Configuration\Format\FormatInterface;

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

    /**
     * @var FormatInterface
     */
    protected $format;

    public function __construct(OutputInterface $output, FormatInterface $format)
    {
        $this->output = $output;
        $this->format = $format;
    }

    /**
     * {@inheritDoc}
     */
    public function write(ConfigurationInterface $configuration)
    {
        $this->output->writeln($this->format->format($configuration));
    }
}
