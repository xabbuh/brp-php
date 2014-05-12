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
use Xabbuh\BRP\Solution\Solution;
use Xabbuh\BRP\Solution\Movement;

/**
 * Base algorithm class.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
abstract class BaseAlgorithm implements AlgorithmInterface
{
    /**
     * {@inheritDoc}
     */
    public function solve(ConfigurationInterface $configuration)
    {
        $solution = new Solution();
        $solution->setInitialConfiguration($configuration);

        while (!$configuration->isEmpty()) {
            $movement = $this->calculateNextMovement($configuration);

            if (null !== $movement) {
                $solution->addMovement($movement);
                $configuration = $movement->apply($configuration);
            }
        }

        return $solution;
    }
    /**
     * {@inheritDoc}
     */
    public function calculateNextMovement(ConfigurationInterface $configuration)
    {
        $targetContainer = $configuration->getLowestContainer();

        if (null === $targetContainer) {
            return null;
        }

        $stack = $configuration->getStackContainingContainer($targetContainer);
        $n = $configuration->getTop($stack);

        if ($n === $targetContainer) {
            // immediately retrieve the container if it is the target container
            return Movement::createRetrieveMovement($stack);
        } else {
            // otherwise relocate it
            return $this->relocateContainer($stack, $configuration);
        }
    }

    /**
     * Relocate a top container.
     *
     * @param int                    $targetStack         The target stack
     * @param ConfigurationInterface $configuration The container configuration
     *
     * @return \Xabbuh\BRP\Solution\MovementInterface
     */
    abstract protected function relocateContainer($targetStack, ConfigurationInterface $configuration);
}
