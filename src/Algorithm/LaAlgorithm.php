<?php

/*
 * This file is part of the Block Relocation Problem package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\BRP\Algorithm;

use Xabbuh\BRP\Configuration\ConfigurationInterface;
use Xabbuh\BRP\Solution\Movement;

/**
 * Solve a BRP using the LA algorithm described by Petering et al.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class LaAlgorithm extends BaseAlgorithm
{
    /**
     * {@inheritDoc}
     */
    protected function relocateContainer($stack, ConfigurationInterface $configuration)
    {
        $otherStacksLowestContainers = $this->getOtherStacksLowestContainers($stack, $configuration);

        if (count($otherStacksLowestContainers['all'][0])) {
            $toStack = $otherStacksLowestContainers['all'][0][0];

            return Movement::createRelocateMovement($stack, $toStack);
        } elseif (count($otherStacksLowestContainers['higher']) === 0) {
            $highestContainer = max(array_keys($otherStacksLowestContainers['all']));
            /** @var int $highestContainerStack */
            $highestContainerStack = $otherStacksLowestContainers['all'][$highestContainer];

            return Movement::createRelocateMovement($stack, $highestContainerStack);
        } else {
            $lowestContainer = min(array_keys($otherStacksLowestContainers['higher']));
            $lowestContainerStack = $otherStacksLowestContainers['higher'][$lowestContainer];

            return Movement::createRelocateMovement($stack, $lowestContainerStack);
        }
    }

    /**
     * Returns the lowest containers from all but one stack.
     *
     * @param int                    $stack         The stack to ignore
     * @param ConfigurationInterface $configuration The container configuration
     *
     * @return array
     */
    private function getOtherStacksLowestContainers($stack, ConfigurationInterface $configuration)
    {
        $n = $configuration->getTop($stack);
        $lowestContainers = array(
            'all' => array(0 => array()),
            'higher' => array(),
        );

        for ($s = 0; $s < $configuration->getStackCount(); $s++) {
            if ($s === $stack) {
                continue;
            }

            if ($configuration->isStackEmpty($s)) {
                $lowestContainers['all'][0][] = $s;
                continue;
            }

            $lowestContainer = $configuration->getLowestContainerInStack($s);
            $lowestContainers['all'][$lowestContainer] = $s;

            if ($lowestContainer > $n) {
                $lowestContainers['higher'][$lowestContainer] = $s;
            }
        }

        return $lowestContainers;
    }
}
