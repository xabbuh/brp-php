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

use Xabbuh\BRP\Configuration\Configuration;

/**
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class MovementTest extends \PHPUnit_Framework_TestCase
{
    public function testGetTypeWithRetrieveMovement()
    {
        $movement = $this->createRetrieveMovement();

        $this->assertEquals(MovementInterface::TYPE_RETRIEVE, $movement->getType());
    }

    public function testGetTypeWithRelocateMovement()
    {
        $movement = $this->createRelocateMovement();

        $this->assertEquals(MovementInterface::TYPE_RELOCATE, $movement->getType());
    }

    public function testGetOriginStack()
    {
        $retrieveMovement = $this->createRetrieveMovement();
        $relocateMovement = $this->createRelocateMovement();

        $this->assertEquals(3, $retrieveMovement->getOriginStack());
        $this->assertEquals(3, $relocateMovement->getOriginStack());
    }

    public function testGetDestinationStack()
    {
        $movement = $this->createRelocateMovement();

        $this->assertEquals(8, $movement->getDestinationStack());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testGetDestinationStackThrowsExceptionForRetrieveMovements()
    {
        $this->createRetrieveMovement()->getDestinationStack();
    }

    public function testApplyOnRetrieveMovement()
    {
        $movement = Movement::createRetrieveMovement(0);
        $configuration = $this->createConfiguration(5, array(
            array(6, 7, 9, 5, 4),
            array(),
            array(8),
        ));
        $appliedConfiguration = $movement->apply($configuration);

        $this->assertNotSame($configuration, $appliedConfiguration);
        $this->assertEquals(
            $this->createConfiguration(5, array(
                array(6, 7, 9, 5),
                array(),
                array(8),
            )),
            $appliedConfiguration
        );
    }

    public function testApplyOnRelocateMovement()
    {
        $movement = Movement::createRelocateMovement(1, 0);
        $configuration = $this->createConfiguration(5, array(
            array(6, 7, 9, 5),
            array(1, 3, 4),
            array(2, 8),
        ));
        $appliedConfiguration = $movement->apply($configuration);

        $this->assertNotSame($configuration, $appliedConfiguration);
        $this->assertEquals(
            $this->createConfiguration(5, array(
                array(6, 7, 9, 5, 4),
                array(1, 3),
                array(2, 8),
            )),
            $appliedConfiguration
        );
    }

    public function testCreateRetrieveMovement()
    {
        $this->assertEquals($this->createRetrieveMovement(), Movement::createRetrieveMovement(3));
    }

    public function testCreateRelocateMovement()
    {
        $this->assertEquals($this->createRelocateMovement(), Movement::createRelocateMovement(3, 8));
    }

    private function createRetrieveMovement()
    {
        return new Movement(MovementInterface::TYPE_RETRIEVE, 3);
    }

    private function createRelocateMovement()
    {
        return new Movement(MovementInterface::TYPE_RELOCATE, 3, 8);
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
