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
    public function addStack(array $elements = array())
    {
        foreach ($elements as $element) {
            if (!is_int($element)) {
                throw new \InvalidArgumentException('Only int elements can be stored');
            }
        }

        $this->stacks[] = $elements;
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
    public function getHeight($stack)
    {
        if (!isset($this->stacks[$stack])) {
            throw new \InvalidArgumentException('Stack '.$stack.' does not exist');
        }

        return count($this->stacks[$stack]);
    }

    /**
     * {@inheritDoc}
     */
    public function getTop($stack)
    {
        if (!isset($this->stacks[$stack])) {
            throw new \InvalidArgumentException('Stack '.$stack.' does not exist');
        }

        if (0 === count($this->stacks[$stack])) {
            throw new \RuntimeException('Stack '.$stack.' is empty');
        }

        return $this->getElement($stack, $this->getHeight($stack) - 1);
    }

    /**
     * {@inheritDoc}
     */
    public function getElement($stack, $index)
    {
        if (!isset($this->stacks[$stack])) {
            throw new \InvalidArgumentException('Stack '.$stack.' does not exist');
        }

        if (0 === count($this->stacks[$stack])) {
            throw new \RuntimeException('Stack '.$stack.' is empty');
        }

        return $this->stacks[$stack][$index];
    }

    /**
     * {@inheritDoc}
     */
    public function push($stack, $element)
    {
        if (!isset($this->stacks[$stack])) {
            throw new \InvalidArgumentException('Stack '.$stack.' does not exist');
        }

        $this->stacks[$stack][] = $element;
    }

    /**
     * {@inheritDoc}
     */
    public function pop($stack)
    {
        if (!isset($this->stacks[$stack])) {
            throw new \InvalidArgumentException('Stack '.$stack.' does not exist');
        }

        if (0 === count($this->stacks[$stack])) {
            throw new \RuntimeException('Stack '.$stack.' is empty');
        }

        return array_pop($this->stacks[$stack]);
    }
}
