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

use Xabbuh\BRP\Configuration\Parser\ParserInterface;

/**
 * Base class for concrete loader implementations.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
abstract class AbstractLoader implements LoaderInterface
{
    /**
     * @var ParserInterface A parser instance
     */
    private $parser;

    /**
     * @param ParserInterface $parser The parser used to parse formatted configurations
     */
    public function __construct(ParserInterface $parser)
    {
        $this->parser = $parser;
    }

    /**
     * {@inheritDoc}
     */
    public function load()
    {
        return $this->parser->parse($this->doLoad());
    }

    /**
     * The actual load process which must be implemented by concrete subclasses.
     *
     * The concrete loaders must return a formatted configuration. This formatted
     * configuration is then parsed in the load method.
     *
     * @return string A formatted configuration
     *
     * @throws \RuntimeException when the resource cannot be parsed
     */
    abstract protected function doLoad();
}
