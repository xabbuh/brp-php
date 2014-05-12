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
use Xabbuh\BRP\Solution\Movement;

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

        $this->assertEquals(
            Movement::createRetrieveMovement(1),
            $this->algorithm->calculateNextMovement($problem)
        );
    }

    public function testRelocateOnEmptyStack()
    {
        $problem = $this->createConfiguration(array(
            array(6, 7, 9, 4, 3),
            array(),
            array(2, 8, 5),
        ));

        $this->assertEquals(
            Movement::createRelocateMovement(2, 1),
            $this->algorithm->calculateNextMovement($problem)
        );
    }

    public function testRelocateOnLowestLowContainer()
    {
        $problem = $this->createConfiguration(array(
            array(4, 1, 7, 2),
            array(3, 5),
            array(10, 8, 9),
        ));

        $this->assertEquals(
            Movement::createRelocateMovement(0, 1),
            $this->algorithm->calculateNextMovement($problem)
        );
    }

    public function testRelocateOnHighestLowContainer()
    {
        $problem = $this->createConfiguration(array(
            array(2, 1, 7, 20),
            array(3, 5),
            array(10, 8, 9),
        ));

        $this->assertEquals(
            Movement::createRelocateMovement(0, 2),
            $this->algorithm->calculateNextMovement($problem)
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

        $this->assertEquals(17, count($solution));
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
