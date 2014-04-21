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
use Xabbuh\BRP\Solution\SolutionStep;

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
        $solution->addSolutionStep(new SolutionStep('initial BRP configuration', clone $configuration));

        while (!$configuration->isEmpty()) {
            $solutionStep = $this->calculateSubsequentConfiguration($configuration);

            if (null !== $solutionStep) {
                $solution->addSolutionStep($solutionStep);
                $configuration = $solutionStep->getConfiguration();
            }
        }

        return $solution;
    }
    /**
     * {@inheritDoc}
     */
    public function calculateSubsequentConfiguration(ConfigurationInterface $configuration)
    {
        $targetContainer = $configuration->getLowestContainer();

        if (null === $targetContainer) {
            return null;
        }

        $stack = $configuration->getStackContainingContainer($targetContainer);
        $n = $configuration->getTop($stack);

        if ($n === $targetContainer) {
            // immediately retrieve the container if it is the target container
            return $this->retrieveContainer($stack, $configuration);
        } else {
            // otherwise relocate it
            return $this->relocateContainer($stack, $configuration);
        }
    }

    /**
     * Retrieves the top container from the stack.
     *
     * @param int                    $stack
     * @param ConfigurationInterface $configuration
     *
     * @return \Xabbuh\BRP\Solution\SolutionStepInterface
     */
    abstract protected function retrieveContainer($stack, ConfigurationInterface $configuration);

    /**
     * Relocate a top container.
     *
     * @param int                    $targetStack         The target stack
     * @param ConfigurationInterface $configuration The container configuration
     *
     * @return \Xabbuh\BRP\Solution\SolutionStepInterface
     */
    abstract protected function relocateContainer($targetStack, ConfigurationInterface $configuration);
}
