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
 * SolutionStepInterface.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
interface SolutionStepInterface
{
    /**
     * Sets the human readable description of the step.
     *
     * @param string $description The description
     */
    public function setDescription($description);

    /**
     * Returns the human readable description of the step.
     *
     * @return string The description
     */
    public function getDescription();

    /**
     * Sets the configuration produced by the step.
     *
     * @param ConfigurationInterface $configuration The configuration
     */
    public function setConfiguration(ConfigurationInterface $configuration);

    /**
     * Returns the configuration produced by the step.
     *
     * @return ConfigurationInterface The configuration
     */
    public function getConfiguration();
}
