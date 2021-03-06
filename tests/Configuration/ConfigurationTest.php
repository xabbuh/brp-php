<?php

/*
 * This file is part of the Block Relocation Problem package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\BRP\Configuration;

/**
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider allConfigurationsProvider
     */
    public function testAddStack(ConfigurationTestData $data)
    {
        $configuration = $data->configuration;
        $configuration->addStack(array(15, 42));
        $this->assertFalse($data->configuration->isEmpty());
        $this->assertEquals(count($data->stackHeights) + 1, $configuration->getStackCount());
        $this->assertFalse($configuration->isStackEmpty(count($data->stackHeights)));
        $this->assertEquals(2, $configuration->getHeight(count($data->stackHeights)));
    }

    /**
     * @dataProvider allConfigurationsProvider
     */
    public function testAddEmptyStack(ConfigurationTestData $data)
    {
        $configuration = $data->configuration;
        $isEmpty = $configuration->isEmpty();
        $configuration->addStack(array());
        $this->assertEquals($isEmpty, $data->configuration->isEmpty());
        $this->assertEquals(count($data->stackHeights) + 1, $configuration->getStackCount());
        $this->assertTrue($configuration->isStackEmpty(count($data->stackHeights)));
        $this->assertEquals(0, $configuration->getHeight(count($data->stackHeights)));
    }

    /**
     * @dataProvider emptyConfigurationProvider
     */
    public function testSetMaxHeight(ConfigurationTestData $data)
    {
        $data->configuration->setMaxHeight(5);

        $this->assertEquals(5, $data->configuration->getMaxHeight());
    }

    /**
     * @dataProvider emptyConfigurationProvider
     */
    public function testSetMaxHeightWithoutLimit(ConfigurationTestData $data)
    {
        $data->configuration->setMaxHeight(-1);

        $this->assertEquals(-1, $data->configuration->getMaxHeight());
    }

    /**
     * @dataProvider emptyConfigurationProvider
     * @expectedException \InvalidArgumentException
     */
    public function testSetMaxHeightToZero(ConfigurationTestData $data)
    {
        $data->configuration->setMaxHeight(0);
    }

    /**
     * @dataProvider allConfigurationsProvider
     */
    public function testGetMaxHeightWithoutExplicitlySetMaxHeight(ConfigurationTestData $data)
    {
        $this->assertEquals(-1, $data->configuration->getMaxHeight());
    }

    /**
     * @dataProvider allConfigurationsProvider
     */
    public function testGetStackCount(ConfigurationTestData $data)
    {
        $this->assertEquals(count($data->stackHeights), $data->configuration->getStackCount());
    }

    /**
     * @dataProvider allConfigurationsProvider
     */
    public function testGetContainersCount(ConfigurationTestData $data)
    {
        if ($data->isEmpty) {
            $this->assertEquals(0, $data->configuration->getContainersCount());
        } else {
            $this->assertEquals(array_sum($data->stackHeights), $data->configuration->getContainersCount());
        }
    }

    /**
     * @dataProvider allConfigurationsProvider
     */
    public function testIsEmpty(ConfigurationTestData $data)
    {
        if ($data->isEmpty) {
            $this->assertTrue($data->configuration->isEmpty());
        } else {
            $this->assertFalse($data->configuration->isEmpty());
        }
    }

    /**
     * @dataProvider allConfigurationsProvider
     */
    public function testGetHeight(ConfigurationTestData $data)
    {
        $configuration = $data->configuration;

        for ($stack = 0; $stack < $configuration->getStackCount(); $stack++) {
            $this->assertEquals($data->stackHeights[$stack], $configuration->getHeight($stack));
        }
    }

    /**
     * @dataProvider allConfigurationsProvider
     */
    public function testIsStackEmpty(ConfigurationTestData $data)
    {
        $configuration = $data->configuration;

        for ($stack = 0; $stack < $configuration->getStackCount(); $stack++) {
            if ($configuration->isStackEmpty($stack)) {
                $this->assertTrue($configuration->isStackEmpty($stack));
            } else {
                $this->assertFalse($configuration->isStackEmpty($stack));
            }
        }
    }

    /**
     * @dataProvider allConfigurationsProvider
     */
    public function testIsStackFull(ConfigurationTestData $data)
    {
        $configuration = $data->configuration;

        for ($stack = 0; $stack < $configuration->getStackCount(); $stack++) {
            $this->assertFalse($configuration->isStackFull($stack));
        }
    }

    /**
     * @dataProvider heightLimitedConfigurationProvider
     */
    public function testIsStackFullWithHeightLimitedConfiguration(ConfigurationTestData $data)
    {
        $this->assertTrue($data->configuration->isStackFull(0));
        $this->assertFalse($data->configuration->isStackFull(1));
        $this->assertFalse($data->configuration->isStackFull(2));
    }

    /**
     * @dataProvider allConfigurationsProvider
     */
    public function testGetTop(ConfigurationTestData $data)
    {
        $configuration = $data->configuration;

        for ($stack = 0; $stack < $configuration->getStackCount(); $stack++) {
            if (!$configuration->isStackEmpty($stack)) {
                $this->assertEquals($data->topContainers[$stack], $configuration->getTop($stack));
            }
        }
    }

    /**
     * @dataProvider configurationWithEmptyStackProvider
     * @expectedException \RuntimeException
     */
    public function testGetPopWithAnEmptyStack(ConfigurationTestData $data)
    {
        $data->configuration->getTop(2);
    }

    /**
     * @dataProvider allConfigurationsProvider
     */
    public function testGetContainers(ConfigurationTestData $data)
    {
        $this->assertEquals($data->containers, $data->configuration->getContainers());
    }

    /**
     * @dataProvider allConfigurationsProvider
     */
    public function testGetContainer(ConfigurationTestData $data)
    {
        $configuration = $data->configuration;

        for ($stack = 0; $stack < $configuration->getStackCount(); $stack++) {
            if (!$configuration->isStackEmpty($stack)) {
                $this->assertEquals(
                    $data->bottomContainers[$stack],
                    $configuration->getContainer($stack, 0)
                );
            }
        }
    }

    /**
     * @dataProvider configurationWithEmptyStackProvider
     * @expectedException \RuntimeException
     */
    public function testGetContainerOnEmptyStack(ConfigurationTestData $data)
    {
        $data->configuration->getContainer(2, 0);
    }

    /**
     * @dataProvider allConfigurationsProvider
     */
    public function testGetLowestContainer(ConfigurationTestData $data)
    {
        $configuration = $data->configuration;
        $lowestContainers = array_filter($data->lowestContainers, function ($container) {
            return null !== $container;
        });

        if (count($lowestContainers) === 0) {
            $lowestContainer = null;
        } else {
            $lowestContainer = min($lowestContainers);
        }

        if (!$configuration->isEmpty()) {
            $this->assertEquals($lowestContainer, $configuration->getLowestContainer());
        }
    }

    /**
     * @dataProvider emptyConfigurationProvider
     * @expectedException \RuntimeException
     */
    public function testGetLowestContainerWithEmptyConfiguration(ConfigurationTestData $data)
    {
        $data->configuration->getLowestContainer();
    }

    /**
     * @dataProvider allConfigurationsProvider
     */
    public function testGetLowestContainerInStack(ConfigurationTestData $data)
    {
        $configuration = $data->configuration;

        for ($stack = 0; $stack < $configuration->getStackCount(); $stack++) {
            if (!$configuration->isStackEmpty($stack)) {
                $this->assertEquals(
                    $data->lowestContainers[$stack],
                    $configuration->getLowestContainerInStack($stack)
                );
            }
        }
    }

    /**
     * @dataProvider configurationWithEmptyStackProvider
     * @expectedException \RuntimeException
     */
    public function testGetLowestContainerInEmptyStack(ConfigurationTestData $data)
    {
        $data->configuration->getLowestContainerInStack(2);
    }

    /**
     * @dataProvider allConfigurationsProvider
     */
    public function testPush(ConfigurationTestData $data)
    {
        $configuration = $data->configuration;

        for ($stack = 0; $stack < $configuration->getStackCount(); $stack++) {
            $configuration->push($stack, 50);
            $this->assertEquals($data->stackHeights[$stack] + 1, $configuration->getHeight($stack));
            $this->assertEquals(50, $configuration->getTop($stack));
        }
    }

    /**
     * @dataProvider heightLimitedConfigurationProvider
     */
    public function testPushOnHeightLimitedStack(ConfigurationTestData $data)
    {
        $configuration = $data->configuration;

        for ($stack = 0; $stack < $configuration->getStackCount(); $stack++) {
            if (!$configuration->isStackFull($stack)) {
                $configuration->push($stack, 50);
                $this->assertEquals($data->stackHeights[$stack] + 1, $configuration->getHeight($stack));
                $this->assertEquals(50, $configuration->getTop($stack));
            }
        }
    }

    /**
     * @dataProvider heightLimitedConfigurationProvider
     * @expectedException \RuntimeException
     */
    public function testPushOnFullStack(ConfigurationTestData $data)
    {
        $data->configuration->push(0, 50);
    }

    /**
     * @dataProvider allConfigurationsProvider
     */
    public function testPop(ConfigurationTestData $data)
    {
        $configuration = $data->configuration;

        for ($stack = 0; $stack < $configuration->getStackCount(); $stack++) {
            if (!$configuration->isStackEmpty($stack)) {
                $this->assertEquals($data->topContainers[$stack], $configuration->pop($stack));
                $this->assertEquals($data->stackHeights[$stack] - 1, $configuration->getHeight($stack));
            }
        }
    }

    /**
     * @dataProvider configurationWithEmptyStackProvider
     * @expectedException \RuntimeException
     */
    public function testPopWithAnEmptyStack(ConfigurationTestData $data)
    {
        $data->configuration->pop(2);
    }

    /**
     * @dataProvider configurationProvider
     */
    public function testGetStackContainingContainer(ConfigurationTestData $data)
    {
        $this->assertEquals(1, $data->configuration->getStackContainingContainer(3));
    }

    /**
     * @dataProvider emptyConfigurationProvider
     * @expectedException \RuntimeException
     */
    public function testGetStackContainingContainerWithEmptyConfiguration(ConfigurationTestData $data)
    {
        $data->configuration->getStackContainingContainer(3);
    }

    /**
     * @dataProvider configurationWithEmptyStackProvider
     * @expectedException \RuntimeException
     */
    public function testGetStackContainingContainerWhitoutContainingThree(ConfigurationTestData $data)
    {
        $data->configuration->getStackContainingContainer(3);
    }

    /**
     * @dataProvider allConfigurationsProvider
     */
    public function testStackContainsContainer(ConfigurationTestData $data)
    {
        $configuration = $data->configuration;

        for ($stack = 0; $stack < $configuration->getStackCount(); $stack++) {
            if ($stack === $data->stackContainingThree) {
                $this->assertTrue($configuration->stackContainsContainer($stack, 3));
            } else {
                $this->assertFalse($configuration->stackContainsContainer($stack, 3));
            }
        }
    }

    public function allConfigurationsProvider()
    {
        return array(
            array($this->createConfiguration()),
            array($this->createEmptyConfiguration()),
            array($this->createConfigurationWithEmptyStack()),
        );
    }

    public function configurationProvider()
    {
        return array(array($this->createConfiguration()));
    }

    public function emptyConfigurationProvider()
    {
        return array(array($this->createEmptyConfiguration()));
    }

    public function configurationWithEmptyStackProvider()
    {
        return array(array($this->createConfigurationWithEmptyStack()));
    }

    public function heightLimitedConfigurationProvider()
    {
        return array(array($this->createHeightLimitedConfiguration()));
    }

    public function createConfiguration()
    {
        $configuration = new Configuration();
        $configuration->addStack(array(6, 7, 9, 4));
        $configuration->addStack(array(1, 3));
        $configuration->addStack(array(2, 8, 5));

        $testData = new ConfigurationTestData();
        $testData->configuration = $configuration;
        $testData->isEmpty = false;
        $testData->stackHeights = array(4, 2, 3);
        $testData->containers = array(6, 7, 9, 4, 1, 3, 2, 8, 5);
        $testData->topContainers = array(4, 3, 5);
        $testData->bottomContainers = array(6, 1, 2);
        $testData->lowestContainers = array(4, 1, 2);
        $testData->stackContainingThree = 1;

        return $testData;
    }

    public function createEmptyConfiguration()
    {
        $configuration = new Configuration();

        $testData = new ConfigurationTestData();
        $testData->configuration = $configuration;
        $testData->isEmpty = true;
        $testData->stackHeights = array();
        $testData->containers = array();
        $testData->topContainers = array();
        $testData->bottomContainers = array();
        $testData->lowestContainers = array();
        $testData->stackContainingThree = null;

        return $testData;
    }

    public function createConfigurationWithEmptyStack()
    {
        $configuration = new Configuration();
        $configuration->addStack(array(6, 7, 9, 4));
        $configuration->addStack(array(5, 8));
        $configuration->addStack(array());

        $testData = new ConfigurationTestData();
        $testData->configuration = $configuration;
        $testData->isEmpty = false;
        $testData->stackHeights = array(4, 2, 0);
        $testData->containers = array(6, 7, 9, 4, 5, 8);
        $testData->topContainers = array(4, 8, null);
        $testData->bottomContainers = array(6, 5, null);
        $testData->lowestContainers = array(4, 5, null);
        $testData->stackContainingThree = null;

        return $testData;
    }

    public function createHeightLimitedConfiguration()
    {
        $data = $this->createConfiguration();
        $data->configuration->setMaxHeight(4);

        return $data;
    }
}

class ConfigurationTestData
{
    /**
     * @var Configuration
     */
    public $configuration;

    /**
     * @var bool
     */
    public $isEmpty;

    /**
     * @var int[]
     */
    public $stackHeights;

    /**
     * @var int[]
     */
    public $containers;

    /**
     * @var int[]
     */
    public $topContainers;

    /**
     * @var int[]
     */
    public $bottomContainers;

    /**
     * @var int[]
     */
    public $lowestContainers;

    /**
     * @var int
     */
    public $stackContainingThree;
}
