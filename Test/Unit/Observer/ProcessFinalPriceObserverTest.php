<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Test\Unit\Observer;

use EPuzzle\CustomerPrice\Observer\ProcessFinalPriceObserver;
use EPuzzle\CustomerPrice\Pricing\Price\CustomerPrice;
use EPuzzle\CustomerPrice\Pricing\Price\CustomerPriceFactory;
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
     * @var CustomerPrice|MockObject
     */
    private CustomerPrice $customerPrice;

    /**
     * @var CustomerPriceFactory|MockObject
     */
    private CustomerPriceFactory $customerPriceFactory;

    /**
     * @var ProcessFinalPriceObserver
     */
    private ProcessFinalPriceObserver $processFinalPriceObserver;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->customerPrice = $this->createMock(CustomerPrice::class);
        $this->customerPriceFactory = $this->createMock(CustomerPriceFactory::class);
        $this->customerPriceFactory->expects($this->any())
            ->method('create')
            ->willReturn($this->customerPrice);
        $this->processFinalPriceObserver = new ProcessFinalPriceObserver($this->customerPriceFactory);
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
            ->onlyMethods(['setFinalPrice'])
            ->getMock();
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
        $this->customerPrice->expects($this->once())
            ->method('getValue')
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
