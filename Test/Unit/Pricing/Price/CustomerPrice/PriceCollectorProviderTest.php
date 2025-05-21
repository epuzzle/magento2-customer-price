<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Test\Unit\Pricing\Price\CustomerPrice;

use EPuzzle\CustomerPrice\Model\ConfigProvider;
use EPuzzle\CustomerPrice\Pricing\Price\CustomerPrice\PriceCollector;
use EPuzzle\CustomerPrice\Pricing\Price\CustomerPrice\PriceCollectorProvider;
use Magento\Framework\Exception\InvalidArgumentException;
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
     * @see PriceCollectorProvider::get()
     */
    public function testGetValue(
        string $collectorType,
        string $expectedValue
    ): void {
        $this->configProvider->expects($this->any())
            ->method('getCollectorType')
            ->willReturn($collectorType);
        $collector = $this->createMock($expectedValue);
        $priceCollectorProvider = new PriceCollectorProvider(
            $this->configProvider,
            [$collectorType => $collector]
        );
        $this->assertInstanceOf($expectedValue, $priceCollectorProvider->get($collectorType));
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
