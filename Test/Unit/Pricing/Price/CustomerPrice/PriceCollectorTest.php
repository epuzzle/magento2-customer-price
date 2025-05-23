<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Test\Unit\Pricing\Price\CustomerPrice;

use EPuzzle\CustomerPrice\Model\Customer\CustomerProviderInterface;
use EPuzzle\CustomerPrice\Model\CustomerPrice\PriceResolver;
use EPuzzle\CustomerPrice\Pricing\Price\CustomerPrice\PriceCollector;
use Magento\Catalog\Model\Product;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @see PriceCollector
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class PriceCollectorTest extends TestCase
{
    /**
     * @var CustomerProviderInterface|MockObject
     */
    private CustomerProviderInterface $customerProvider;

    /**
     * @var PriceResolver|MockObject
     */
    private PriceResolver $customerPriceResolver;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->customerProvider = $this->createMock(CustomerProviderInterface::class);
        $this->customerPriceResolver = $this->createMock(PriceResolver::class);
    }

    /**
     * @dataProvider dataProvider
     * @param int $customerId
     * @param int $websiteId
     * @param int $productId
     * @param float $qty
     * @param float $expectedValue
     * @see PriceCollector::collect()
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
            ->onlyMethods(['getId'])
            ->getMock();
        $product->expects($this->once())
            ->method('getId')
            ->willReturn($productId);

        $priceCollector = new PriceCollector(
            $this->customerProvider,
            $this->customerPriceResolver
        );

        $this->assertEquals($expectedValue, $priceCollector->collect($product, $qty));
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
