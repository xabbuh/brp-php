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

/**
 * Solution of a block relocation problem.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class Solution implements SolutionInterface
{
    /**
     * @var SolutionStepInterface[] The steps performed to solve the problem
     */
    private $steps = array();

    /**
     * {@inheritDoc}
     */
    public function addSolutionStep(SolutionStepInterface $step)
    {
        $this->steps[] = $step;
    }

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        return count($this->steps);
    }

    /**
     * {@inheritDoc}
     */
    public function getSolutionStep($step)
    {
        if (!isset($this->steps[$step])) {
            throw new \InvalidArgumentException('Solution step '.$step.' does not exist');
        }

        return $this->steps[$step];
    }
}
