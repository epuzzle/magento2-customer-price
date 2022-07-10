<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Test\Unit\Observer;

use Jeysmook\CustomerPrices\Api\CustomerProviderInterface;
use Jeysmook\CustomerPrices\Model\Command\CustomerPrice\PriceResolver;
use Jeysmook\CustomerPrices\Observer\ProcessFinalPriceObserver;
use Magento\Catalog\Model\Product;
use Magento\Framework\Event;
use Magento\Framework\Event\Observer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @see ProcessFinalPriceObserver
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class ProcessFinalPriceObserverTest extends TestCase
{
    /**
     * @var CustomerProviderInterface|MockObject
     */
    private $customerProvider;

    /**
     * @var PriceResolver|MockObject
     */
    private $customerPriceResolver;

    /**
     * @var ProcessFinalPriceObserver
     */
    private $processFinalPriceObserver;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->customerProvider = $this->createMock(CustomerProviderInterface::class);
        $this->customerPriceResolver = $this->createMock(PriceResolver::class);
        $this->processFinalPriceObserver = new ProcessFinalPriceObserver(
            $this->customerProvider,
            $this->customerPriceResolver
        );
    }

    /**
     * @dataProvider dataProvider
     * @param int $customerId
     * @param int $websiteId
     * @param int $productId
     * @param float $qty
     * @param float $expectedValue
     * @see ProcessFinalPriceObserver::execute()
     */
    public function testExecute(
        int $customerId,
        int $websiteId,
        int $productId,
        float $qty,
        float $expectedValue
    ): void {
        $product = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['setFinalPrice', 'getId'])
            ->getMock();
        $product->expects($this->once())
            ->method('getId')
            ->willReturn($productId);
        $product->expects($this->once())
            ->method('setFinalPrice')
            ->with($expectedValue)
            ->willReturnSelf();
        $event = $this->getMockBuilder(Event::class)
            ->disableOriginalConstructor()
            ->addMethods(['getProduct', 'getQty'])
            ->getMock();
        $event->expects($this->once())
            ->method('getProduct')
            ->willReturn($product);
        $event->expects($this->once())
            ->method('getQty')
            ->willReturn($qty);
        $observer = $this->createMock(Observer::class);
        $observer->expects($this->any())
            ->method('getEvent')
            ->willReturn($event);
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
        $this->processFinalPriceObserver->execute($observer);
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
