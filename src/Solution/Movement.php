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
class Movement implements MovementInterface
{
    /**
     * @var int The movement type
     */
    private $type;

    /**
     * @var int The origin stack
     */
    private $originStack;

    /**
     * @var int The destination stack
     */
    private $destinationStack;

    /**
     * @param int $type             The movement type
     * @param int $originStack      The origin stack
     * @param int $destinationStack The destination stack (must be null for
     *                              retrieve movements)
     *
     * @throws \InvalidArgumentException if any invalid values is parsed to
     *                                   the constructor
     */
    public function __construct($type, $originStack, $destinationStack = null)
    {
        if (self::TYPE_RETRIEVE !== $type && self::TYPE_RELOCATE !== $type) {
            throw new \InvalidArgumentException($type.' is no valid movement type');
        }

        if (!is_int($originStack) || $originStack < 0) {
            throw new \InvalidArgumentException('The origin stack must be a positive integer');
        }

        if (self::TYPE_RETRIEVE === $type && null !== $destinationStack) {
            throw new \InvalidArgumentException('The destination stack must be null');
        }

        if (self::TYPE_RELOCATE === $type && (!is_int($destinationStack) || $destinationStack < 0)) {
            throw new \InvalidArgumentException('The destination stack must be a positive integer');
        }

        $this->type = $type;
        $this->originStack = $originStack;
        $this->destinationStack = $destinationStack;
    }

    /**
     * {@inheritDoc}
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * {@inheritDoc}
     */
    public function getOriginStack()
    {
        return $this->originStack;
    }

    /**
     * {@inheritDoc}
     */
    public function getDestinationStack()
    {
        if (self::TYPE_RETRIEVE === $this->type) {
            throw new \RuntimeException('Retrieve movements have no destination stack');
        }

        return $this->destinationStack;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(ConfigurationInterface $configuration)
    {
        $newConfiguration = clone $configuration;
        $container = $newConfiguration->pop($this->originStack);

        if (MovementInterface::TYPE_RELOCATE === $this->type) {
            $newConfiguration->push($this->destinationStack, $container);
        }

        return $newConfiguration;
    }

    /**
     * {@inheritDoc}
     */
    public static function createRetrieveMovement($originStack)
    {
        return new Movement(self::TYPE_RETRIEVE, $originStack);
    }

    /**
     * {@inheritDoc}
     */
    public static function createRelocateMovement($originStack, $destinationStack)
    {
        return new Movement(self::TYPE_RELOCATE, $originStack, $destinationStack);
    }
}
