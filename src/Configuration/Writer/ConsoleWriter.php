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
class ConsoleWriter extends AbstractWriter
{
    /**
     * @var OutputInterface
     */
    private $output;

    public function __construct(FormatInterface $format, OutputInterface $output)
    {
        parent::__construct($format);

        $this->output = $output;
    }

    /**
     * {@inheritDoc}
     */
    protected function doFormattedWrite($formattedConfiguration)
    {
        $this->output->writeln($formattedConfiguration);
    }
}
