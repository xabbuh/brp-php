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
 * SolutionInterface.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
interface SolutionInterface extends \Countable
{
    /**
     * Adds a {@link SolutionStepInterface step} to the solution.
     *
     * @param SolutionStepInterface $step The step to add
     */
    public function addSolutionStep(SolutionStepInterface $step);

    /**
     * Returns the nth step of the solution.
     *
     * @param int $step The number of the step to return
     *
     * @return SolutionStepInterface The step
     *
     * @throws \InvalidArgumentException if the step is not between 0 (inclusive)
     *                                   and the number of solution steps (exclusive)
     */
    public function getSolutionStep($step);
}
