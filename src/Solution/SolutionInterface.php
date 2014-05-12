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
 * SolutionInterface.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
interface SolutionInterface extends \Countable
{
    /**
     * Sets the initial container configuration.
     *
     * @param ConfigurationInterface $configuration The initial configuration
     */
    public function setInitialConfiguration(ConfigurationInterface $configuration);

    /**
     * Returns the initial configuration.
     *
     * @return ConfigurationInterface The initial configuration
     */
    public function getInitialConfiguration();

    /**
     * Adds a {@link MovementInterface movement} to the solution.
     *
     * @param MovementInterface $movement The movement to add
     */
    public function addMovement(MovementInterface $movement);

    /**
     * Returns the nth movement of the solution.
     *
     * @param int $movement The number of the movement to return
     *
     * @return MovementInterface The movement
     *
     * @throws \InvalidArgumentException if the movement is not between 0 (inclusive)
     *                                   and the total number of movements (exclusive)
     */
    public function getMovement($movement);
}
