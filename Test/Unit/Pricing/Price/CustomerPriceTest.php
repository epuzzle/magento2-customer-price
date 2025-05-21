<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Test\Unit\Pricing\Price;

use EPuzzle\CustomerPrice\Pricing\Price\CustomerPrice;
use EPuzzle\CustomerPrice\Pricing\Price\CustomerPrice\PriceCollector;
use Magento\Catalog\Model\Product;
use Magento\Framework\Pricing\Adjustment\CalculatorInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * @see PriceCollector
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class CustomerPriceTest extends TestCase
{
    /**
     * @var CalculatorInterface|MockObject
     */
    private CalculatorInterface $calculator;

    /**
     * @var PriceCurrencyInterface|MockObject
     */
    private PriceCurrencyInterface $priceCurrency;

    /**
     * @var CustomerPrice\PriceCollectorInterface|MockObject
     */
    private CustomerPrice\PriceCollectorInterface $priceCollector;

    /**
     * @var CustomerPrice\PriceCollectorProvider|MockObject
     */
    private CustomerPrice\PriceCollectorProvider $priceCollectorProvider;

    /**
     * @var LoggerInterface|MockObject
     */
    private LoggerInterface $logger;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->calculator = $this->createMock(CalculatorInterface::class);
        $this->priceCurrency = $this->createMock(PriceCurrencyInterface::class);
        $this->priceCollector = $this->createMock(CustomerPrice\PriceCollectorInterface::class);
        $this->priceCollectorProvider = $this->createMock(CustomerPrice\PriceCollectorProvider::class);
        $this->logger = $this->createMock(LoggerInterface::class);
    }

    /**
     * @dataProvider dataProvider
     * @param int $customerId
     * @param int $websiteId
     * @param int $productId
     * @param float $qty
     * @param float $expectedValue
     * @see CustomerPrice::getValue()
     */
    public function testGetValue(
        int $customerId,
        int $websiteId,
        int $productId,
        float $qty,
        float $expectedValue
    ): void {
        $product = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->priceCollectorProvider->expects($this->once())
            ->method('get')
            ->willReturn($this->priceCollector);
        $this->priceCollector->expects($this->once())
            ->method('collect')
            ->willReturn($expectedValue);
        $customerPrice = new CustomerPrice(
            $product,
            $qty,
            $this->calculator,
            $this->priceCurrency,
            $this->priceCollectorProvider,
            $this->logger
        );
        $this->assertEquals($expectedValue, $customerPrice->getValue());
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            // [customerId, websiteId, productId, qty, expectedValue]
            [1, 1, 1, 1.0, 10.0],
            [1, 1, 1, 2.0, 9.0],
            [1, 1, 1, 3.0, 8.0],
            [2, 1, 2, 1.0, 20.0],
        ];
    }
}
