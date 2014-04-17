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
 * ConfigurationInterface.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
interface ConfigurationInterface
{
    /**
     * Adds a new stack to the configuration.
     *
     * @param int[] $elements Optional elements to add to the stack
     *
     * @throws \InvalidArgumentException if any of the elements is not an integer
     */
    public function addStack(array $elements = array());

    /**
     * Returns the number of stacks in the configuration.
     */
    public function getStackCount();

    /**
     * Returns the current height of the given stack.
     *
     * @param int $stack The stack to test
     *
     * @return int The height
     *
     * @throws \InvalidArgumentException if no stack exists for the given number
     */
    public function getHeight($stack);

    /**
     * Returns the top element of the given stack.
     *
     * @param int $stack The number of the stack to return the element from
     *
     * @return int The element
     *
     * @throws \InvalidArgumentException if no stack exists for the given number
     * @throws \RuntimeException         when the stack is empty
     */
    public function getTop($stack);

    /**
     * Returns the top element of the given stack.
     *
     * @param int $stack The number of the stack to return the element from
     * @param int $index Index of the element (0 means the top element)
     *
     * @return int The element
     *
     * @throws \InvalidArgumentException if no stack exists for the given number
     *                                   or an element does not exist at the
     *                                   given index
     * @throws \RuntimeException         when the stack is empty
     */
    public function getElement($stack, $index);

    /**
     * Pushes an element on top of a stack.
     *
     * @param int $stack   The stack on which to push
     * @param int $element The element to push
     *
     * @throws \InvalidArgumentException if no stack exists for the given number
     */
    public function push($stack, $element);

    /**
     * Pops the top element of the given stack and returns it.
     *
     * @param int $stack The stack from which top pop
     *
     * @return int The old top element
     *
     * @throws \InvalidArgumentException if no stack exists for the given number
     * @throws \RuntimeException         when the stack is empty
     */
    public function pop($stack);
}
