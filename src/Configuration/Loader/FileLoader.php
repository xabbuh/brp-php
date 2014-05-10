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
 * File configuration loader.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class FileLoader extends AbstractLoader
{
    /**
     * @var string The filename
     */
    private $file;

    /**
     * @param ParserInterface $parser The parser used to parse the file contents
     * @param string          $file   The file to load
     */
    public function __construct(ParserInterface $parser, $file)
    {
        parent::__construct($parser);

        $this->file = $file;
    }

    /**
     * {@inheritDoc}
     */
    protected function doLoad()
    {
        if (!is_file($this->file)) {
            throw new \RuntimeException($this->file.' is no file');
        }

        if (!is_readable($this->file)) {
            throw new \RuntimeException($this->file.' is not readable');
        }

        return file_get_contents($this->file);
    }
}
