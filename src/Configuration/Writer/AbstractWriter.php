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
use Xabbuh\BRP\Configuration\Format\FormatInterface;

/**
 * Base class for writer implementations working on formatted configurations.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
abstract class AbstractWriter implements WriterInterface
{
    /**
     * @var FormatInterface A format instance
     */
    private $format;

    /**
     * @param FormatInterface $format A format instance
     */
    public function __construct(FormatInterface $format)
    {
        $this->format = $format;
    }

    /**
     * {@inheritDoc}
     */
    public function write(ConfigurationInterface $configuration)
    {
        $this->doFormattedWrite($this->format->format($configuration));
    }

    /**
     * Writes a formatted configuration.
     *
     * This method must be implemented by concrete writer implementations and
     * will be called after the configuration has been formatted.
     *
     * @param string $formattedConfiguration The formatted configuration to be
     *                                       written by the implementing writer
     */
    abstract protected function doFormattedWrite($formattedConfiguration);
}
