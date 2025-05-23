<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Test\Unit\Pricing\Price\CustomerPrice;

use EPuzzle\CustomerPrice\Model\ConfigProvider;
use EPuzzle\CustomerPrice\Pricing\Price\CustomerPrice\PriceCollector;
use EPuzzle\CustomerPrice\Pricing\Price\CustomerPrice\PriceCollectorProvider;
use Magento\Framework\Exception\InvalidArgumentException;
use Magento\Framework\Exception\StateException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @see PriceCollectorProvider
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class PriceCollectorProviderTest extends TestCase
{
    /**
     * @var ConfigProvider|MockObject
     */
    private ConfigProvider $configProvider;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->configProvider = $this->createMock(ConfigProvider::class);
    }

    /**
     * @dataProvider dataProvider
     * @param string $collectorType
     * @param string $expectedValue
     * @throws InvalidArgumentException
     * @throws StateException
     * @see PriceCollectorProvider::get()
     */
    public function testGetValue(
        string $collectorType,
        string $expectedValue
    ): void {
        $collector = $this->createMock($expectedValue);
        $this->configProvider->expects($this->any())
            ->method('isEnabled')
            ->willReturn(true);
        $this->configProvider->expects($this->any())
            ->method('getCollectorByType')
            ->willReturn($collector);
        $collectorProvider = new PriceCollectorProvider($this->configProvider);
        $this->assertInstanceOf($expectedValue, $collectorProvider->get());
    }

    /**
     * @dataProvider dataProvider
     * @param string $collectorType
     * @param string $expectedValue
     * @throws InvalidArgumentException
     * @throws StateException
     * @see PriceCollectorProvider::get()
     */
    public function testModuleDisabled(
        string $collectorType,
        string $expectedValue
    ) {
        $collector = $this->createMock($expectedValue);
        $this->configProvider->expects($this->any())
            ->method('isEnabled')
            ->willReturn(false);
        $this->configProvider->expects($this->any())
            ->method('getCollectorByType')
            ->willReturn($collector);
        $collectorProvider = new PriceCollectorProvider($this->configProvider);
        $this->expectException(StateException::class);
        $this->expectExceptionMessage('The module is disabled.');
        $collectorProvider->get();
    }

    /**
     * @dataProvider dataProvider
     * @param string $collectorType
     * @param string $expectedValue
     * @throws InvalidArgumentException
     * @throws StateException
     * @see PriceCollectorProvider::get()
     */
    public function testInvalidCollectorType(
        string $collectorType,
        string $expectedValue
    ) {
        $this->configProvider->expects($this->any())
            ->method('isEnabled')
            ->willReturn(true);
        $this->configProvider->expects($this->any())
            ->method('getCollectorByType')
            ->willReturn(null);
        $this->configProvider->expects($this->any())
            ->method('getCollectorType')
            ->willReturn($collectorType);
        $collectorProvider = new PriceCollectorProvider($this->configProvider);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Invalid price collector type: %s', $collectorType));
        $collectorProvider->get();
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            // [collectorType, expectedValue]
            ['default', PriceCollector::class]
        ];
    }
}
