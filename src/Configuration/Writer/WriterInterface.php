<?php

/*
 * This file is part of the Block Relocation Problem package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\BRP\Configuration\Writer;

use Xabbuh\BRP\Configuration\ConfigurationInterface;

/**
 * Configuration writer definition.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
interface WriterInterface
{
    /**
     * Writes a configuration.
     *
     * @param ConfigurationInterface $configuration The configuration to write
     */
    public function write(ConfigurationInterface $configuration);
}
