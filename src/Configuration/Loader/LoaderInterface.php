<?php

/*
 * This file is part of the Block Relocation Problem package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\BRP\Configuration\Loader;

/**
 * Loader interface.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
interface LoaderInterface
{
    /**
     * Loads a {@link \Xabbuh\BRP\Configuration\ConfigurationInterface configuration}
     * from a loader specific resource.
     *
     * @return \Xabbuh\BRP\Configuration\ConfigurationInterface The loaded configuration
     *
     * @throws \RuntimeException when the resource cannot be parsed
     */
    public function load();
}
