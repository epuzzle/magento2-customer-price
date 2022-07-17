<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Test\Unit\Plugin\Pricing\Render\PriceBox;

use EPuzzle\CustomerPrice\Model\Customer\CustomerProviderInterface;
use EPuzzle\CustomerPrice\Model\Customer\ExistingCustomerPriceByStrategy;
use EPuzzle\CustomerPrice\Plugin\Pricing\Render\PriceBox\AddCacheTagsToPriceBox;
use Magento\Framework\Pricing\Render\PriceBox;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @see AddCacheTagsToPriceBox
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class AddCacheTagsToPriceBoxTest extends TestCase
{
    /**
     * @var CustomerProviderInterface|MockObject
     */
    private CustomerProviderInterface $customerProvider;

    /**
     * @var ExistingCustomerPriceByStrategy|MockObject
     */
    private ExistingCustomerPriceByStrategy $existingCustomerPriceByStrategy;

    /**
     * @var AddCacheTagsToPriceBox
     */
    private AddCacheTagsToPriceBox $addCacheTagsToPriceBox;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->customerProvider = $this->createMock(CustomerProviderInterface::class);
        $this->existingCustomerPriceByStrategy = $this->createMock(
            ExistingCustomerPriceByStrategy::class
        );
        $this->addCacheTagsToPriceBox = new AddCacheTagsToPriceBox(
            $this->customerProvider,
            $this->existingCustomerPriceByStrategy
        );
    }

    /**
     * @dataProvider dataProvider
     * @param int $customerId
     * @param string $cacheKey
     * @param string $expectedValue
     * @see AddCacheTagsToPriceBox::afterGetCacheKey()
     */
    public function testAfterGetCacheKey(int $customerId, string $cacheKey, string $expectedValue): void
    {
        $this->customerProvider->expects($this->once())
            ->method('getCustomerId')
            ->willReturn($customerId);

        if ($customerId) {
            $this->existingCustomerPriceByStrategy->expects($this->once())
                ->method('execute')
                ->with($customerId, ExistingCustomerPriceByStrategy::STRATEGY_CUSTOMER)
                ->willReturn(true);
        }

        $priceBox = $this->createMock(PriceBox::class);

        $this->assertEquals(
            $expectedValue,
            $this->addCacheTagsToPriceBox->afterGetCacheKey($priceBox, $cacheKey)
        );
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            // [customerId, cacheKey, expectedValue]
            [1, 'cache', 'cache-1'],
            [2, 'cache', 'cache-2'],
            [3, 'cache', 'cache-3'],
            [0, 'cache', 'cache']
        ];
    }
}
