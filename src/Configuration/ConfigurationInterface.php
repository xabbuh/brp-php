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
     * @param int[] $containers Optional containers to add to the stack
     *
     * @throws \InvalidArgumentException if any of the containers is not an integer
     */
    public function addStack(array $containers = array());

    /**
     * Returns the number of stacks in the configuration.
     *
     * @return int The number of stacks
     */
    public function getStackCount();

    /**
     * Returns the number of containers in all stacks.
     *
     * @return int The total number of containers
     */
    public function getContainersCount();

    /**
     * Checks whether all stacks are empty.
     *
     * @return bool True, if all stacks are empty, false otherwise
     */
    public function isEmpty();

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
     * Checks whether a stack is empty.
     *
     * @param int $stack The stack to test
     *
     * @return bool True, if the stack is empty, false otherwise
     *
     * @throws \InvalidArgumentException if no stack exists for the given number
     */
    public function isStackEmpty($stack);

    /**
     * Returns the top container of the given stack.
     *
     * @param int $stack The number of the stack to return the container from
     *
     * @return int The container
     *
     * @throws \InvalidArgumentException if no stack exists for the given number
     * @throws \RuntimeException         when the stack is empty
     */
    public function getTop($stack);

    /**
     * Returns the top container of the given stack.
     *
     * @param int $stack The number of the stack to return the container from
     * @param int $index Index of the container (0 means the top container)
     *
     * @return int The container
     *
     * @throws \InvalidArgumentException if no stack exists for the given number
     *                                   or a container does not exist at the
     *                                   given index
     * @throws \RuntimeException         when the stack is empty
     */
    public function getContainer($stack, $index);

    /**
     * Returns the lowest container of all stacks.
     *
     * @return int The container
     */
    public function getLowestContainer();

    /**
     * Returns the lowest container of the given stack.
     *
     * @param int $stack The number of the stack to return the lowest container from
     *
     * @return int The container
     *
     * @throws \InvalidArgumentException if no stack exists for the given number
     * @throws \RuntimeException         when the stack is empty
     */
    public function getLowestContainerInStack($stack);

    /**
     * Pushes a container on top of a stack.
     *
     * @param int $stack   The stack on which to push
     * @param int $container The container to push
     *
     * @throws \InvalidArgumentException if no stack exists for the given number
     */
    public function push($stack, $container);

    /**
     * Pops the top container off the given stack and returns it.
     *
     * @param int $stack The stack from which to pop the container off
     *
     * @return int The container being popped off
     *
     * @throws \InvalidArgumentException if no stack exists for the given number
     * @throws \RuntimeException         when the stack is empty
     */
    public function pop($stack);

    /**
     * Searches for a stack containing the given container.
     *
     * @param int $container The container to search for
     *
     * @return int The stack containing the container
     *
     * @throws \RuntimeException if the container could not be found
     */
    public function getStackContainingContainer($container);

    /**
     * Checks if a stack contains a container.
     *
     * @param int $stack     The stack to search in
     * @param int $container The container to search for
     *
     * @return bool True, if the stack contains the container, false otherwise
     *
     * @throws \InvalidArgumentException if no stack exists for the given number
     */
    public function stackContainsContainer($stack, $container);
}
