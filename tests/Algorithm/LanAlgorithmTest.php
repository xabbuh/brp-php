<?php

/*
 * This file is part of the Block Relocation Problem package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\BRP\Algorithm;

use Xabbuh\BRP\Configuration\Configuration;
use Xabbuh\BRP\Configuration\ConfigurationInterface;

/**
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class LanAlgorithmTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LanAlgorithm
     */
    private $la5Algorithm;

    protected function setUp()
    {
        $this->la5Algorithm = new LanAlgorithm(5);

        if ('testRelocate' !== $this->getName()) {
//            $this->markTestSkipped();
        }
    }

    public function testSolve()
    {
        $configuration = $this->createConfiguration(5, array(
            array(6, 7, 9),
            array(1, 3, 4),
            array(2, 8, 5),
        ));
        $solution = $this->la5Algorithm->solve($configuration);

        $this->assertEquals(18, count($solution));
    }

    public function testRetrieveTop()
    {
        $configuration = $this->createConfiguration(5, array(
            array(6, 7, 9, 5, 4),
            array(),
            array(8),
        ));

        $this->assertEquals(
            $this->createConfiguration(5, array(
                array(6, 7, 9, 5),
                array(),
                array(8),
            )),
            $this->processConfiguration($this->la5Algorithm, $configuration)
        );
    }

    public function testRelocate()
    {
        $configuration = $this->createConfiguration(5, array(
            array(6, 7, 9, 5),
            array(1, 3, 4),
            array(2, 8),
        ));

        $this->assertEquals(
            $this->createConfiguration(5, array(
                array(6, 7, 9, 5, 4),
                array(1, 3),
                array(2, 8),
            )),
            $this->processConfiguration($this->la5Algorithm, $configuration)
        );
    }

    public function testRelocateTopOfTargetStack()
    {
        $configuration = $this->createConfiguration(5, array(
            array(6, 7, 9, 5),
            array(1, 3, 4),
            array(2, 8),
        ));

        $this->assertEquals(
            $this->createConfiguration(5, array(
                array(6, 7, 9, 5, 4),
                array(1, 3),
                array(2, 8),
            )),
            $this->processConfiguration($this->la5Algorithm, $configuration)
        );
    }

    public function testCleaningMove()
    {
        $configuration = $this->createConfiguration(5, array(
            array(6, 7, 9),
            array(1, 3, 4),
            array(2, 8, 5),
        ));

        $this->assertEquals(
            $this->createConfiguration(5, array(
                array(6, 7, 9, 5),
                array(1, 3, 4),
                array(2, 8),
            )),
            $this->processConfiguration($this->la5Algorithm, $configuration)
        );
    }

    public function testRelocateWhenAStackIsFull()
    {
        $configuration = $this->createConfiguration(5, array(
            array(6, 7, 9, 5, 4),
            array(1, 3),
            array(2, 8),
        ));

        $this->assertEquals(
            $this->createConfiguration(5, array(
                array(6, 7, 9, 5, 4),
                array(1),
                array(2, 8, 3),
            )),
            $this->processConfiguration($this->la5Algorithm, $configuration)
        );
    }

    public function testRelocateOntoEmptyStack()
    {
        $configuration = $this->createConfiguration(5, array(
            array(6, 7, 9, 5, 4),
            array(),
            array(2, 8, 3),
        ));

        $this->assertEquals(
            $this->createConfiguration(5, array(
                array(6, 7, 9, 5, 4),
                array(3),
                array(2, 8),
            )),
            $this->processConfiguration($this->la5Algorithm, $configuration)
        );
    }

    private function processConfiguration(LanAlgorithm $algorithm, ConfigurationInterface $configuration)
    {
        return $algorithm
            ->calculateSubsequentConfiguration($configuration)
            ->getConfiguration();
    }

    private function createConfiguration($maxHeight, array $stacks = array())
    {
        $configuration = new Configuration();
        $configuration->setMaxHeight($maxHeight);

        foreach ($stacks as $stack) {
            $configuration->addStack($stack);
        }

        return $configuration;
    }

    private function createConfigurationWithHeightLimit()
    {
        $configuration = new Configuration();
        $configuration->addStack(array(4, 7, 5, 9));
        $configuration->addStack(array(12, 14, 10, 13));
        $configuration->addStack(array(11, 6, 8));
        $configuration->addStack(array(1, 3));
        $configuration->addStack(array(15));
        $configuration->addStack(array(2, 17));
        $configuration->addStack(array(16));

        return $configuration;
    }
}
