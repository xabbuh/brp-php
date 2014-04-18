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
use Xabbuh\BRP\Solution\SolutionInterface;
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

        $targetContainer = $configuration->getLowestContainer();
        $steps = 0;
        $maxSteps = 20;

        while (null !== $targetContainer && !$configuration->isEmpty() && $steps <= $maxSteps) {
            $stack = $configuration->getStackContainingContainer($targetContainer);
            $n = $configuration->getTop($stack);

            if ($n === $targetContainer) {
                // immediately retrieve the container if it is the target container
                $this->retrieveContainer($stack, $configuration, $solution);
                $targetContainer = $configuration->getLowestContainer();
            } else {
                // otherwise relocate it
                $this->relocateTopContainer($stack, $configuration, $solution);
            }

            $steps++;
        }

        return $solution;
    }

    /**
     * Retrieves the top container from the stack.
     *
     * @param int                    $stack
     * @param ConfigurationInterface $configuration
     * @param SolutionInterface      $solution
     */
    private function retrieveContainer($stack, ConfigurationInterface $configuration, SolutionInterface $solution)
    {
        $container = $configuration->pop($stack);
        $solution->addSolutionStep(new SolutionStep(
            'Retrieve target container '.$container,
            clone $configuration
        ));
    }

    /**
     * Relocate the top container of a given stack.
     *
     * @param int                    $stack
     * @param ConfigurationInterface $configuration
     * @param SolutionInterface      $solution
     */
    private function relocateTopContainer($stack, ConfigurationInterface $configuration, SolutionInterface $solution)
    {
        $otherStacksLowestContainers = $this->getOtherStacksLowestContainers($stack, $configuration);

        if (count($otherStacksLowestContainers['all'][0])) {
            $toStack = $otherStacksLowestContainers['all'][0][0];
            $this->doRelocateContainer($stack, $toStack, $configuration, $solution);
        } elseif (count($otherStacksLowestContainers['higher']) === 0) {
            $highestContainer = max(array_keys($otherStacksLowestContainers['all']));
            $highestContainerStack = $otherStacksLowestContainers['all'][$highestContainer];
            $this->doRelocateContainer($stack, $highestContainerStack, $configuration, $solution);
        } else {
            $lowestContainer = min(array_keys($otherStacksLowestContainers['higher']));
            $lowestContainerStack = $otherStacksLowestContainers['higher'][$lowestContainer];
            $this->doRelocateContainer($stack, $lowestContainerStack, $configuration, $solution);
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
     * @param SolutionInterface      $solution      The solution
     */
    private function doRelocateContainer($fromStack, $toStack, ConfigurationInterface $configuration, SolutionInterface $solution)
    {
        $container = $configuration->pop($fromStack);
        $configuration->push($toStack, $container);
        $solution->addSolutionStep(new SolutionStep(
            'Relocate container '.$container.' from stack '.$fromStack.' to stack '.$toStack,
            clone $configuration
        ));
    }
}
