<?php

/*
 * This file is part of the Block Relocation Problem package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\BRP\Solution\Dumper;

use Xabbuh\BRP\Solution\SolutionInterface;

/**
 * Dumper interface.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
interface DumperInterface
{
    /**
     * Dumps the given solution.
     *
     * @param SolutionInterface $solution The solution to dump
     */
    public function dump(SolutionInterface $solution);
}
