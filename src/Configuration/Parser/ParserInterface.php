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

/**
 * Parse a well defined format into a configuration instance.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
interface ParserInterface
{
    /**
     * Parses a formatted configuration.
     *
     * @param string $formattedConfiguration The formatted configuration to parse
     *
     * @return \Xabbuh\BRP\Configuration\ConfigurationInterface The parsed configuration
     */
    public function parse($formattedConfiguration);
}
