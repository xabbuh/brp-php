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
 * Solution of a block relocation problem.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class Solution implements SolutionInterface
{
    /**
     * @var ConfigurationInterface The initial container configuration
     */
    private $initialConfiguration;

    /**
     * {@inheritDoc}
     */
    public function setInitialConfiguration(ConfigurationInterface $configuration)
    {
        $this->initialConfiguration = $configuration;
    }

    /**
     * {@inheritDoc}
     */
    public function getInitialConfiguration()
    {
        return $this->initialConfiguration;
    }

    /**
     * @var MovementInterface[] The movements performed to solve the problem
     */
    private $movements = array();

    /**
     * {@inheritDoc}
     */
    public function addMovement(MovementInterface $movement)
    {
        $this->movements[] = $movement;
    }

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        return count($this->movements);
    }

    /**
     * {@inheritDoc}
     */
    public function getMovement($movement)
    {
        if (!isset($this->movements[$movement])) {
            throw new \InvalidArgumentException('Movement '.$movement.' does not exist');
        }

        return $this->movements[$movement];
    }
}
