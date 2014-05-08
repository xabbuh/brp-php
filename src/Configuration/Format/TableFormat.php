<?php

/*
 * This file is part of the Block Relocation Problem package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\BRP\Configuration\Format;

use Symfony\Component\Console\Helper\TableHelper;
use Symfony\Component\Console\Output\BufferedOutput;
use Xabbuh\BRP\Configuration\ConfigurationInterface;

/**
 * Format a configuration as a table which can be used in console outputs.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class TableFormat implements FormatInterface
{
    /**
     * {@inheritDoc}
     */
    public function format(ConfigurationInterface $configuration)
    {
        $output = new BufferedOutput();
        $tableHelper = $this->createTableHelper();

        $maxHeight = $this->getHighestStackHeight($configuration);

        $tableHelper->setHeaders(range(0, $configuration->getStackCount() - 1));

        for ($level = $maxHeight - 1; $level >= 0; $level--) {
            $tableHelper->addRow($this->createTableRow($configuration, $level));
        }

        $tableHelper->render($output);

        if ($configuration->getMaxHeight() > 0) {
            $output->writeln('');
            $output->writeln('Max stack height: '.$configuration->getMaxHeight());
        }

        return $output->fetch();
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
    private function createTableHelper()
    {
        $tableHelper = new TableHelper();
        $tableHelper->setLayout(TableHelper::LAYOUT_COMPACT);
        $tableHelper->setPadType(STR_PAD_LEFT);

        return $tableHelper;
    }
}
