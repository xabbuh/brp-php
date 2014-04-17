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

use Xabbuh\BRP\Configuration\Configuration;

/**
 * Loader implementation for JSON files.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class JsonFileLoader implements LoaderInterface
{
    /**
     * {@inheritDoc}
     */
    public function load($resource)
    {
        $configuration = new Configuration();
        $jsonConfiguration = json_decode(file_get_contents($resource));

        foreach ($jsonConfiguration->stacks as $stack) {
            $configuration->addStack($stack);
        }

        return $configuration;
    }

    /**
     * {@inheritDoc}
     */
    public function supports($resource)
    {
        return '.json' === substr($resource, -5);
    }
}
