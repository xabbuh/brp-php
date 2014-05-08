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

use Symfony\Component\Console\Helper\TableHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Xabbuh\BRP\Configuration\ConfigurationInterface;

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
    public function write(ConfigurationInterface $configuration)
    {
        $tableHelper = $this->getTableHelper();
        $maxHeight = $this->getHighestStackHeight($configuration);

        $tableHelper->setHeaders(range(0, $configuration->getStackCount() - 1));

        for ($level = $maxHeight - 1; $level >= 0; $level--) {
            $tableHelper->addRow($this->createTableRow($configuration, $level));
        }

        $tableHelper->render($this->output);

        if ($configuration->getMaxHeight() > 0) {
            $this->output->writeln('');
            $this->output->writeln('Max stack height: '.$configuration->getMaxHeight());
        }
    }

    /**
     * Creates a table row showing a certain level of all stacks.
     *
     * @param ConfigurationInterface $configuration The container configuration
     * @param int                    $row           The row to produce
     *
     * @return array The table row
     */
    private function createTableRow(ConfigurationInterface $configuration, $row)
    {
        $tableRow = array();

        for ($stack = 0; $stack < $configuration->getStackCount(); $stack++) {
            if ($row < $configuration->getHeight($stack)) {
                $tableRow[] = $configuration->getContainer($stack, $row);
            } else {
                $tableRow[] = '';
            }
        }

        return $tableRow;
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

    /**
     * Initializes the table helper.
     *
     * @return TableHelper The table helper
     */
    private function getTableHelper()
    {
        $tableHelper = new TableHelper();
        $tableHelper->setLayout(TableHelper::LAYOUT_COMPACT);
        $tableHelper->setPadType(STR_PAD_LEFT);

        return $tableHelper;
    }
}
