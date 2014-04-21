<?php

/*
 * This file is part of the Block Relocation Problem package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\BRP\Configuration;

/**
 * A container configuration.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class Configuration implements ConfigurationInterface
{
    private $stacks = array();

    private $containers = array();

    private $containersCount = 0;

    /**
     * {@inheritDoc}
     */
    public function addStack(array $containers = array())
    {
        foreach ($containers as $container) {
            if (!is_int($container)) {
                throw new \InvalidArgumentException('Only int containers can be stored');
            }
        }

        $index = count($this->stacks);
        $this->stacks[$index] = array();

        foreach ($containers as $container) {
            $this->push($index, $container);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getStackCount()
    {
        return count($this->stacks);
    }

    /**
     * {@inheritDoc}
     */
    public function getContainersCount()
    {
        return $this->containersCount;
    }

    /**
     * {@inheritDoc}
     */
    public function isEmpty()
    {
        return 0 === $this->containersCount;
    }

    /**
     * {@inheritDoc}
     */
    public function getHeight($stack)
    {
        $this->checkStackExists($stack);

        return count($this->stacks[$stack]);
    }

    /**
     * {@inheritDoc}
     */
    public function isStackEmpty($stack)
    {
        $this->checkStackExists($stack);

        return $this->getHeight($stack) === 0;
    }

    /**
     * {@inheritDoc}
     */
    public function getTop($stack)
    {
        $this->checkStackNotEmpty($stack);

        return $this->getContainer($stack, $this->getHeight($stack) - 1);
    }

    /**
     * {@inheritDoc}
     */
    public function getContainer($stack, $index)
    {
        $this->checkStackNotEmpty($stack);

        return $this->stacks[$stack][$index];
    }

    /**
     * {@inheritDoc}
     */
    public function getLowestContainer()
    {
        if ($this->isEmpty()) {
            throw new \RuntimeException('Unable to retrieve the lowest container of an empty configuration');
        }

        $lowestContainers = array();

        for ($stack = 0; $stack < $this->getStackCount(); $stack++) {
            if ($this->isStackEmpty($stack)) {
                continue;
            }

            $lowestContainers[] = $this->getLowestContainerInStack($stack);
        }

        return min($lowestContainers);
    }

    /**
     * {@inheritDoc}
     */
    public function getLowestContainerInStack($stack)
    {
        $this->checkStackNotEmpty($stack);

        return min($this->stacks[$stack]);
    }

    /**
     * {@inheritDoc}
     */
    public function push($stack, $container)
    {
        $this->checkStackExists($stack);

        $this->stacks[$stack][] = $container;
        $this->containers[$container] = $stack;
        $this->containersCount++;
    }

    /**
     * {@inheritDoc}
     */
    public function pop($stack)
    {
        $this->checkStackNotEmpty($stack);

        $topContainer = array_pop($this->stacks[$stack]);
        unset($this->containers[$topContainer]);
        $this->containersCount--;

        return $topContainer;
    }

    /**
     * {@inheritDoc}
     */
    public function getStackContainingContainer($container)
    {
        if (!isset($this->containers[$container])) {
            throw new \RuntimeException('Stacks do not contain container '.$container);
        }

        return $this->containers[$container];
    }

    /**
     * {@inheritDoc}
     */
    public function stackContainsContainer($stack, $container)
    {
        $this->checkStackExists($stack);

        return in_array($container, $this->stacks[$stack]);
    }

    private function checkStackExists($stack)
    {
        if (!isset($this->stacks[$stack])) {
            throw new \InvalidArgumentException('Stack '.$stack.' does not exist');
        }
    }

    private function checkStackNotEmpty($stack)
    {
        $this->checkStackExists($stack);

        if (0 === count($this->stacks[$stack])) {
            throw new \RuntimeException('Stack '.$stack.' is empty');
        }
    }
}
