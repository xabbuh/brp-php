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
        $this->checkStackExists($stack);

        return count($this->stacks[$stack]);
    }

    /**
     * {@inheritDoc}
     */
    public function getTop($stack)
    {
        $this->checkStackNotEmpty($stack);

        return $this->getElement($stack, $this->getHeight($stack) - 1);
    }

    /**
     * {@inheritDoc}
     */
    public function getElement($stack, $index)
    {
        $this->checkStackNotEmpty($stack);

        return $this->stacks[$stack][$index];
    }

    /**
     * {@inheritDoc}
     */
    public function push($stack, $element)
    {
        $this->checkStackExists($stack);

        $this->stacks[$stack][] = $element;
    }

    /**
     * {@inheritDoc}
     */
    public function pop($stack)
    {
        $this->checkStackNotEmpty($stack);

        return array_pop($this->stacks[$stack]);
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
