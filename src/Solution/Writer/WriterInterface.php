<?php

/*
 * This file is part of the Block Relocation Problem package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\BRP\Solution\Writer;

use Xabbuh\BRP\Solution\SolutionInterface;

/**
 * Writer interface.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
interface WriterInterface
{
    /**
     * Writes the given solution.
     *
     * @param SolutionInterface $solution The solution to write
     */
    public function write(SolutionInterface $solution);
}
