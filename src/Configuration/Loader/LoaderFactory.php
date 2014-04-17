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
 * Loader factory.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class LoaderFactory
{
    /**
     * @var LoaderInterface[] Registered loaders
     */
    protected $loaders;

    public function __construct()
    {
        $this->loaders = array(new JsonFileLoader());
    }

    /**
     * Returns a loader for the given resource.
     *
     * @param string $resource The resource
     *
     * @return LoaderInterface The loader
     *
     * @throws \InvalidArgumentException if no loader could be found
     */
    public function getLoader($resource)
    {
        foreach ($this->loaders as $loader) {
            if ($loader->supports($resource)) {
                return $loader;
            }
        }

        throw new \InvalidArgumentException('No load found for '.$resource);
    }
}
