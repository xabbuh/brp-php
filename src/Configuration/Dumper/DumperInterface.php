<?php

/*
 * This file is part of the Block Relocation Problem package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\BRP\Configuration\Dumper;

use Xabbuh\BRP\Configuration\ConfigurationInterface;

/**
 * Dumper interface.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
interface DumperInterface
{
    /**
     * Dumps the given configuration.
     *
     * @param ConfigurationInterface $configuration The configuration to dump
     */
    public function dump(ConfigurationInterface $configuration);
}
