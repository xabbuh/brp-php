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

        $this->stacks[] = $containers;
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
        $containersCount = 0;

        for ($stack = 0; $stack < $this->getStackCount(); $stack++) {
            $containersCount += $this->getHeight($stack);
        }

        return $containersCount;
    }

    /**
     * {@inheritDoc}
     */
    public function isEmpty()
    {
        for ($stack = 0; $stack < $this->getStackCount(); $stack++) {
            if (!$this->isStackEmpty($stack)) {
                return false;
            }
        }

        return true;
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

        $lowestContainer = null;

        for ($stack = 0; $stack < $this->getStackCount(); $stack++) {
            if ($this->isStackEmpty($stack)) {
                continue;
            }

            $lowestContainerInStack = $this->getLowestContainerInStack($stack);

            if (null === $lowestContainer || $lowestContainerInStack < $lowestContainer) {
                $lowestContainer = $lowestContainerInStack;
            }
        }

        return $lowestContainer;
    }

    /**
     * {@inheritDoc}
     */
    public function getLowestContainerInStack($stack)
    {
        $this->checkStackNotEmpty($stack);

        $lowestContainer = $this->getContainer($stack, 0);

        for ($container = 1; $container < $this->getHeight($stack); $container++) {
            if ($this->getContainer($stack, $container) < $lowestContainer) {
                $lowestContainer = $this->getContainer($stack, $container);
            }
        }

        return $lowestContainer;
    }

    /**
     * {@inheritDoc}
     */
    public function push($stack, $container)
    {
        $this->checkStackExists($stack);

        $this->stacks[$stack][] = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function pop($stack)
    {
        $this->checkStackNotEmpty($stack);

        return array_pop($this->stacks[$stack]);
    }

    /**
     * {@inheritDoc}
     */
    public function getStackContainingContainer($container)
    {
        for ($stack = 0; $stack < $this->getStackCount(); $stack++) {
            if ($this->stackContainsContainer($stack, $container)) {
                return $stack;
            }
        }

        throw new \RuntimeException('Stacks do not contain container '.$container);
    }

    /**
     * {@inheritDoc}
     */
    public function stackContainsContainer($stack, $container)
    {
        $this->checkStackExists($stack);

        for ($i = 0; $i < $this->getHeight($stack); $i++) {
            if ($this->getContainer($stack, $i) === $container) {
                return true;
            }
        }

        return false;
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
