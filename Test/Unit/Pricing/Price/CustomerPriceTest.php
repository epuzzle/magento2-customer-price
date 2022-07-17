<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Test\Unit\Pricing\Price;

use EPuzzle\CustomerPrice\Model\Customer\CustomerProviderInterface;
use EPuzzle\CustomerPrice\Model\CustomerPrice\PriceResolver;
use EPuzzle\CustomerPrice\Pricing\Price\CustomerPrice;
use Magento\Catalog\Model\Product;
use Magento\Framework\Pricing\Adjustment\CalculatorInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Pricing\PriceInfoInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @see CustomerPrice
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
     * @var CustomerProviderInterface|MockObject
     */
    private CustomerProviderInterface $customerProvider;

    /**
     * @var PriceResolver|MockObject
     */
    private PriceResolver $customerPriceResolver;

    /**
     * @var PriceInfoInterface|MockObject
     */
    private PriceInfoInterface $priceInfo;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->calculator = $this->createMock(CalculatorInterface::class);
        $this->priceCurrency = $this->createMock(PriceCurrencyInterface::class);
        $this->customerProvider = $this->createMock(CustomerProviderInterface::class);
        $this->customerPriceResolver = $this->createMock(PriceResolver::class);
        $this->priceInfo = $this->createMock(PriceInfoInterface::class);
    }

    /**
     * @dataProvider dataProvider
     * @param int $customerId
     * @param int $websiteId
     * @param int $productId
     * @param float $qty
     * @param float $expectedValue
     * @see CustomerPrice::getAmount()
     */
    public function testGetValue(
        int $customerId,
        int $websiteId,
        int $productId,
        float $qty,
        float $expectedValue
    ): void {
        $this->customerProvider->expects($this->once())
            ->method('getCustomerId')
            ->willReturn($customerId);
        $this->customerProvider->expects($this->once())
            ->method('getWebsiteId')
            ->willReturn($websiteId);
        $this->customerPriceResolver->expects($this->once())
            ->method('resolve')
            ->with($customerId, $websiteId, $productId, $qty)
            ->willReturn($expectedValue);
        $product = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getId', 'getPriceInfo'])
            ->getMock();
        $product->expects($this->once())
            ->method('getId')
            ->willReturn($productId);
        $product->expects($this->once())
            ->method('getPriceInfo')
            ->willReturn($this->priceInfo);
        $this->priceCurrency->expects($this->once())
            ->method('convertAndRound')
            ->with($expectedValue)
            ->willReturn($expectedValue);

        $customerPrice = new CustomerPrice(
            $product,
            $qty,
            $this->calculator,
            $this->priceCurrency,
            $this->customerProvider,
            $this->customerPriceResolver
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
