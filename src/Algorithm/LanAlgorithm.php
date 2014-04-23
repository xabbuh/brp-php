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
use Xabbuh\BRP\Solution\SolutionStep;

/**
 * Solve a BRP using the LA-N algorithm described by Petering et al.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class LanAlgorithm extends BaseAlgorithm
{
    /**
     * @var int The maximum number of stacks to look ahead
     */
    private $lookAhead;

    public function __construct($lookAhead)
    {
        $this->lookAhead = $lookAhead;
    }

    /**
     * {@inheritDoc}
     */
    protected function retrieveContainer($stack, ConfigurationInterface $configuration)
    {
        $subsequentConfiguration = clone $configuration;
        $container = $subsequentConfiguration->pop($stack);

        return new SolutionStep('Retrieve target container '.$container, $subsequentConfiguration);
    }

    /**
     * {@inheritDoc}
     */
    protected function relocateContainer($targetStack, ConfigurationInterface $configuration)
    {
        $lowestContainers = $this->getNLowestContainers($configuration);

        do {
            $stacksContainingLowestContainers = $this->getStacksContaining(
                $configuration,
                $lowestContainers
            );
            $reduceStackSet = false;

            if (count($stacksContainingLowestContainers) === $configuration->getStackCount()) {
                array_pop($lowestContainers);
                $reduceStackSet = true;
            }
        } while ($reduceStackSet);

        $containerToRelocate = $this->determineContainerToRelocate(
            $configuration,
            $stacksContainingLowestContainers,
            $targetStack
        );
        $relocateFromStack = $configuration->getStackContainingContainer($containerToRelocate);
        $relocationTarget = $this->determineTargetStack($configuration, $containerToRelocate);
        $subsequentConfiguration = clone $configuration;
        $subsequentConfiguration->pop($relocateFromStack);
        $subsequentConfiguration->push($relocationTarget, $containerToRelocate);

        return new SolutionStep(
            sprintf(
                'Relocate container %s from stack %s to stack %s',
                $containerToRelocate,
                $relocateFromStack,
                $relocationTarget
            ),
            $subsequentConfiguration);
    }

    /**
     * Returns the N lowest containers where N is the look ahead value.
     *
     * @param ConfigurationInterface $configuration The container configuration
     *
     * @return int[] The N lowest containers
     */
    private function getNLowestContainers(ConfigurationInterface $configuration)
    {
        $lowestContainers = array();
        $allContainers = $configuration->getContainers();
        sort($allContainers);

        for ($i = 0; $i < $this->lookAhead && $i < count($allContainers); $i++) {
            $lowestContainers[] = $allContainers[$i];
        }

        return $lowestContainers;
    }

    /**
     * Returns the stacks containing the given containers.
     *
     * @param ConfigurationInterface $configuration The container configuration
     * @param int[]                  $containers    The containers to search
     *
     * @return int[] The stacks
     */
    private function getStacksContaining(ConfigurationInterface $configuration, array $containers)
    {
        $stacks = array();

        foreach ($containers as $container) {
            $stack = $configuration->getStackContainingContainer($container);

            if (!in_array($stack, $stacks)) {
                $stacks[] = $stack;
            }
        }

        return $stacks;
    }

    /**
     * Determines the container to relocate.
     *
     * @param ConfigurationInterface $configuration The container configuration
     * @param int[]                  $stacks        Stacks to check
     * @param int                    $targetStack   The target stack
     *
     * @return int The container to relocate
     */
    private function determineContainerToRelocate(ConfigurationInterface $configuration, array $stacks, $targetStack)
    {
        $step = 1;
        $containerDetermined = false;
        $topOnTargetStack = $configuration->getTop($targetStack);

        do {
            $topContainers = array();

            foreach ($stacks as $stack) {
                $topContainers[] = $configuration->getTop($stack);
            }

            $n = $this->getNHighestContainer($topContainers, $step);

            if ($n === $topOnTargetStack || $this->hasGoodCleaningMove($configuration, $n)) {
                $containerDetermined = true;
            }

            $step++;
        } while(!$containerDetermined);

        return $n;
    }

    /**
     * Determines the target stack for a container relocation.
     *
     * @param ConfigurationInterface $configuration The container configuration
     * @param int                    $container     The container to relocate
     *
     * @return int The stack to relocate the container onto
     */
    private function determineTargetStack(ConfigurationInterface $configuration, $container)
    {
        $stackContainingN = $configuration->getStackContainingContainer($container);
        $d = $this->getLowestContainersStack($configuration, $container);
        $lowestStackContainers = $this->getLowestContainersForNonFullStacks($configuration);

        if (isset($lowestStackContainers[$stackContainingN])) {
            unset($lowestStackContainers[$stackContainingN]);
        }

        if (count($d) === 0) {
            arsort($lowestStackContainers);
            $stacks = array_keys($lowestStackContainers);

            if (null === $lowestStackContainers[end($stacks)]) {
                return end($stacks);
            }

            return $stacks[0];
        } else {
            $lowestStackContainers = array_filter($lowestStackContainers, function ($value) use ($container) {
                return null === $value || $value > $container;
            });
            asort($lowestStackContainers);
            $stacks = array_keys($lowestStackContainers);

            if (null === $lowestStackContainers[reset($stacks)]) {
                return reset($stacks);
            }

            return $stacks[0];
        }
    }

    /**
     * Returns the N highest container from a set of containers.
     *
     * @param int[] $containers The set of containers
     * @param int   $n          N
     *
     * @return int The N highest container
     */
    private function getNHighestContainer($containers, $n)
    {
        rsort($containers);

        return $containers[$n - 1];
    }

    /**
     * Checks whether there is a good cleaning move involving the given container.
     *
     * @param ConfigurationInterface $configuration The container configuration
     * @param int                    $n             The container to move
     *
     * @return bool True, if there is a good cleaning move, false otherwise
     */
    private function hasGoodCleaningMove(ConfigurationInterface $configuration, $n)
    {
        $stackContainingN = $configuration->getStackContainingContainer($n);

        if ($configuration->getLowestContainerInStack($stackContainingN) === $n) {
            return false;
        }

        $lowestContainersStacks = $this->getLowestContainersStack($configuration, $n);

        if (count($lowestContainersStacks) === 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Returns the stack where the lowest container is greater than a given container.
     *
     * @param ConfigurationInterface $configuration The container configuration
     * @param int                    $n             The container to compare with
     *
     * @return array The stacks containing lower containers than N
     */
    private function getLowestContainersStack(ConfigurationInterface $configuration, $n)
    {
        $stackContainingN = $configuration->getStackContainingContainer($n);
        $lowestContainersStacks = array();

        for ($stack = 0; $stack < $configuration->getStackCount(); $stack++) {
            if ($stack === $stackContainingN) {
                continue;
            }

            if ($configuration->isStackFull($stack)) {
                continue;
            } elseif ($configuration->isStackEmpty($stack)) {
                $lowestContainersStacks[] = $stack;
                continue;
            }

            $lowestContainerInStack = $configuration->getLowestContainerInStack($stack);

            if ($lowestContainerInStack > $n) {
                $lowestContainersStacks[] = $stack;
            }
        }

        return $lowestContainersStacks;
    }

    private function getLowestContainersForNonFullStacks(ConfigurationInterface $configuration)
    {
        $containers = array();

        for ($stack = 0; $stack < $configuration->getStackCount(); $stack++) {
            if ($configuration->isStackEmpty($stack)) {
                $containers[$stack] = null;
            } elseif (!$configuration->isStackFull($stack)) {
                $containers[$stack] = $configuration->getLowestContainerInStack($stack);
            }
        }

        return $containers;
    }
}
