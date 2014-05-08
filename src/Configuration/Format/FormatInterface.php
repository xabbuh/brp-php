<?php

/*
 * This file is part of the Block Relocation Problem package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\BRP\Configuration\Format;

use Xabbuh\BRP\Configuration\ConfigurationInterface;

/**
 * Format interface.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
interface FormatInterface
{
    /**
     * Formats the given configuration.
     *
     * @param ConfigurationInterface $configuration The configuration to format
     *
     * @return mixed The format created by the format instance
     */
    public function format(ConfigurationInterface $configuration);
}
