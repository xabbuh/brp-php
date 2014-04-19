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
 * Solve a BRP using the LA algorithm described by Petering et al.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class LaAlgorithm implements AlgorithmInterface
{
    /**
     * {@inheritDoc}
     */
    public function solve(ConfigurationInterface $configuration)
    {
        $solution = new Solution();
        $solution->addSolutionStep(new SolutionStep('initial BPR configuration', clone $configuration));

        $steps = 0;
        $maxSteps = 20;

        while (!$configuration->isEmpty() && $steps <= $maxSteps) {
            $solutionStep = $this->calculateSubsequentConfiguration($configuration);

            if (null !== $solutionStep) {
                $solution->addSolutionStep($solutionStep);
            }

            $steps++;
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
            return $this->relocateTopContainer($stack, $configuration);
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
    private function retrieveContainer($stack, ConfigurationInterface $configuration)
    {
        $container = $configuration->pop($stack);

        return new SolutionStep('Retrieve target container '.$container, clone $configuration);
    }

    /**
     * Relocate the top container of a given stack.
     *
     * @param int                    $stack
     * @param ConfigurationInterface $configuration
     *
     * @return \Xabbuh\BRP\Solution\SolutionStepInterface
     */
    private function relocateTopContainer($stack, ConfigurationInterface $configuration)
    {
        $otherStacksLowestContainers = $this->getOtherStacksLowestContainers($stack, $configuration);

        if (count($otherStacksLowestContainers['all'][0])) {
            $toStack = $otherStacksLowestContainers['all'][0][0];
            return $this->doRelocateContainer($stack, $toStack, $configuration);
        } elseif (count($otherStacksLowestContainers['higher']) === 0) {
            $highestContainer = max(array_keys($otherStacksLowestContainers['all']));
            /** @var int $highestContainerStack */
            $highestContainerStack = $otherStacksLowestContainers['all'][$highestContainer];
            return $this->doRelocateContainer($stack, $highestContainerStack, $configuration);
        } else {
            $lowestContainer = min(array_keys($otherStacksLowestContainers['higher']));
            $lowestContainerStack = $otherStacksLowestContainers['higher'][$lowestContainer];
            return $this->doRelocateContainer($stack, $lowestContainerStack, $configuration);
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

    /**
     * Relocates a container from one stack to another.
     *
     * @param int                    $fromStack     Stack to relocate from
     * @param int                    $toStack       Stack to relocate to
     * @param ConfigurationInterface $configuration The container configuration
     *
     * @return \Xabbuh\BRP\Solution\SolutionStepInterface
     */
    private function doRelocateContainer($fromStack, $toStack, ConfigurationInterface $configuration)
    {
        $container = $configuration->pop($fromStack);
        $configuration->push($toStack, $container);

        return new SolutionStep(
            'Relocate container '.$container.' from stack '.$fromStack.' to stack '.$toStack,
            clone $configuration
        );
    }
}
