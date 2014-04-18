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

/**
 * Create BRP solving algorithms.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class AlgorithmFactory
{
    /**
     * Creates an instance of the given algorithm.
     *
     * @param string $name The algorithm name
     *
     * @return AlgorithmInterface The algorithm
     *
     * @throws \InvalidArgumentException if the given algorithm is not known
     */
    public function getAlgorithm($name)
    {
        $className = ucfirst(strtolower($name)).'Algorithm';
        $fullyQualifiedClassName = 'Xabbuh\\BRP\\Algorithm\\'.$className;

        if (!class_exists($fullyQualifiedClassName)) {
            throw new \InvalidArgumentException('There is no algorithm named '.$name);
        }

        return new $fullyQualifiedClassName();
    }
}
