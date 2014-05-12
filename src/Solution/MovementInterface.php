<?php

/*
 * This file is part of the Block Relocation Problem package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\BRP\Solution;

use Xabbuh\BRP\Configuration\ConfigurationInterface;

/**
 * A movement of an item.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
interface MovementInterface
{
    /**
     * A movement in which an item is retrieved from a stack
     */
    const TYPE_RETRIEVE = 1;

    /**
     * A movement in which an item is relocated from one stack onto another
     */
    const TYPE_RELOCATE = 2;

    /**
     * Returns the movement type.
     *
     * The return value is either MovementInterface::RETRIEVE or
     * MovementInterface::RELOCATE.
     *
     * @return int The movement type
     */
    public function getType();

    /**
     * Returns the stack on which the item resided before the movement.
     *
     * @return int The origin stack
     */
    public function getOriginStack();

    /**
     * Returns the destination stack of a relocation movement.
     *
     * @return int The destination stack
     *
     * @throws \RuntimeException if the movement is a retrieve movement
     */
    public function getDestinationStack();

    /**
     * Applies the movement on a container configuration.
     *
     * The given configuration won't be manipulated but a new configuration
     * instance reflecting the movement will be created.
     *
     * @param ConfigurationInterface $configuration
     *
     * @return ConfigurationInterface The new configuration
     */
    public function apply(ConfigurationInterface $configuration);

    /**
     * Creates a movement retrieving a certain item from a particular stack.
     *
     * @param int $originStack The stack the item is retrieved from
     *
     * @return MovementInterface The movement
     */
    public static function createRetrieveMovement($originStack);

    /**
     * Creates a movement relocating an item from one stack onto another.
     *
     * @param int $originStack      The stack the item is being moved from
     * @param int $destinationStack The stack the item is moved onto
     *
     * @return MovementInterface The movement
     */
    public static function createRelocateMovement($originStack, $destinationStack);
}
