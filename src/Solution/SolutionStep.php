<?php

/*
 * This file is part of the Block Relocation Problem package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\BRP\Solution;

use Xabbuh\BRP\Configuration\ConfigurationInterface;

/**
 * A step in a {@link SolutionInterface solution}.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class SolutionStep implements SolutionStepInterface
{
    /**
     * @var string Human readable description of the step
     */
    protected $description;

    /**
     * @var ConfigurationInterface The configuration produced by the step
     */
    protected $configuration;

    public function __construct($description = '', ConfigurationInterface $configuration = null)
    {
        $this->description = $description;
        $this->configuration = $configuration;
    }

    /**
     * {@inheritDoc}
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * {@inheritDoc}
     */
    public function setConfiguration(ConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }
}
