<?php

/*
 * This file is part of the Block Relocation Problem package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\BRP\Configuration\Parser;

use Xabbuh\BRP\Configuration\Configuration;

/**
 * JSON encoded container configurations parser.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class JsonParser implements ParserInterface
{
    /**
     * {@inheritDoc}
     */
    public function parse($formattedConfiguration)
    {
        $configuration = new Configuration();
        $jsonConfiguration = json_decode($formattedConfiguration);

        if (isset($jsonConfiguration->maxHeight)) {
            $configuration->setMaxHeight($jsonConfiguration->maxHeight);
        }

        foreach ($jsonConfiguration->stacks as $stack) {
            $configuration->addStack($stack);
        }

        return $configuration;
    }
}
