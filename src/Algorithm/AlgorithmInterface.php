<?php

/*
 * This file is part of the Block Relocation Problem package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\BRP\Algorithm;

use Xabbuh\BRP\Configuration\ConfigurationInterface;

/**
 * AlgorithmInterface.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
interface AlgorithmInterface
{
    /**
     * Solves the given configuration and returns the solution.
     *
     * @param ConfigurationInterface $configuration The configuration
     *
     * @return \Xabbuh\BRP\Solution\SolutionInterface The solution
     */
    public function solve(ConfigurationInterface $configuration);
}
