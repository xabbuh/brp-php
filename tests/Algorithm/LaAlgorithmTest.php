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

/**
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class LaAlgorithmTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LaAlgorithm
     */
    private $algorithm;

    protected function setUp()
    {
        $this->algorithm = new LaAlgorithm();
    }

    public function testRetrieveTop()
    {
        $problem = $this->createConfiguration(array(
            array(6, 7, 9, 4, 3),
            array(1),
            array(2, 8, 5),
        ));
        $solutionStep = $this->algorithm->calculateSubsequentConfiguration($problem);
        $subsequentConfiguration = $solutionStep->getConfiguration();

        $this->assertEquals(
            $this->createConfiguration(array(
                array(6, 7, 9, 4, 3),
                array(),
                array(2, 8, 5),
            )),
            $subsequentConfiguration
        );
    }

    public function testRelocateOnEmptyStack()
    {
        $problem = $this->createConfiguration(array(
            array(6, 7, 9, 4, 3),
            array(),
            array(2, 8, 5),
        ));
        $solutionStep = $this->algorithm->calculateSubsequentConfiguration($problem);
        $subsequentConfiguration = $solutionStep->getConfiguration();

        $this->assertEquals(
            $this->createConfiguration(array(
                array(6, 7, 9, 4, 3),
                array(5),
                array(2, 8),
            )),
            $subsequentConfiguration
        );
    }

    public function testRelocateOnLowestLowContainer()
    {
        $problem = $this->createConfiguration(array(
            array(4, 1, 7, 2),
            array(3, 5),
            array(10, 8, 9),
        ));
        $solutionStep = $this->algorithm->calculateSubsequentConfiguration($problem);
        $subsequentConfiguration = $solutionStep->getConfiguration();

        $this->assertEquals(
            $this->createConfiguration(array(
                array(4, 1, 7),
                array(3, 5, 2),
                array(10, 8, 9),
            )),
            $subsequentConfiguration
        );
    }

    public function testRelocateOnHighestLowContainer()
    {
        $problem = $this->createConfiguration(array(
            array(2, 1, 7, 20),
            array(3, 5),
            array(10, 8, 9),
        ));
        $solutionStep = $this->algorithm->calculateSubsequentConfiguration($problem);
        $subsequentConfiguration = $solutionStep->getConfiguration();

        $this->assertEquals(
            $this->createConfiguration(array(
                array(2, 1, 7),
                array(3, 5),
                array(10, 8, 9, 20),
            )),
            $subsequentConfiguration
        );
    }

    public function testSolve()
    {
        $configuration = $this->createConfiguration(array(
            array(6, 3, 1, 4),
            array(2, 5, 7, 8),
            array(),
        ));
        $solution = $this->algorithm->solve($configuration);

        $this->assertEquals(18, count($solution));
    }

    private function createConfiguration(array $stacks)
    {
        $configuration = new Configuration();

        foreach ($stacks as $stack) {
            $configuration->addStack($stack);
        }

        return $configuration;
    }
}
