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
use Xabbuh\BRP\Solution\Movement;

/**
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class LanAlgorithmTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LanAlgorithm
     */
    private $la2Algorithm;

    /**
     * @var LanAlgorithm
     */
    private $la5Algorithm;

    protected function setUp()
    {
        $this->la2Algorithm = new LanAlgorithm(2);
        $this->la5Algorithm = new LanAlgorithm(5);
    }

    public function testSolve()
    {
        $configuration = $this->createConfiguration(5, array(
            array(6, 7, 9),
            array(1, 3, 4),
            array(2, 8, 5),
        ));
        $solution = $this->la5Algorithm->solve($configuration);

        $this->assertEquals(17, count($solution));
    }

    public function testRetrieveTop()
    {
        $configuration = $this->createConfiguration(5, array(
            array(6, 7, 9, 5, 4),
            array(),
            array(8),
        ));

        $this->assertEquals(
            Movement::createRetrieveMovement(0),
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
            Movement::createRelocateMovement(1, 0),
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
            Movement::createRelocateMovement(2, 0),
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
            Movement::createRelocateMovement(1, 2),
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
            Movement::createRelocateMovement(2, 1),
            $this->processConfiguration($this->la5Algorithm, $configuration)
        );
    }

    public function testRelocateHighestTopOntoEmptyStack()
    {
        $configuration = $this->createConfiguration(4, array(
            array(6, 3, 1, 4),
            array(2, 5, 7, 8),
            array(),
        ));

        $this->assertEquals(
            Movement::createRelocateMovement(1, 2),
            $this->processConfiguration($this->la2Algorithm, $configuration)
        );
    }

    private function processConfiguration(LanAlgorithm $algorithm, ConfigurationInterface $configuration)
    {
        return $algorithm->calculateNextMovement($configuration);
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
}
