<?php

/**
 * Customer prices for Magento 2 platform
 *
 * @author Dmytro Kaplin <dkaplin1994@gmail.com>
 * @license https://github.com/epuzzle/magento2-customer-price/blob/main/LICENSE
 */

declare(strict_types=1);

namespace ePuzzle\CustomerPrice\Test\Unit\Model;

use ePuzzle\CustomerPrice\Model\CustomerPrice;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

/**
 * @see CustomerPrice
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class CustomerPriceTest extends TestCase
{
    private CustomerPrice $customerPrice;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);
        $this->customerPrice = $objectManager->getObject(CustomerPrice::class);
    }

    /**
     * @see CustomerPrice::setItemId()
     */
    public function testSetItemId(): void
    {
        $expectedValue = 1;
        $this->customerPrice->setItemId($expectedValue);
        $this->assertEquals($expectedValue, $this->customerPrice->getData('item_id'));
    }

    /**
     * @see CustomerPrice::getItemId()
     */
    public function testGetItemId(): void
    {
        $expectedValue = 2;
        $this->customerPrice->setData('item_id', $expectedValue);
        $this->assertEquals($expectedValue, $this->customerPrice->getItemId());
    }

    /**
     * @see CustomerPrice::setCustomerId()
     */
    public function testSetCustomerId(): void
    {
        $expectedValue = 3;
        $this->customerPrice->setCustomerId($expectedValue);
        $this->assertEquals($expectedValue, $this->customerPrice->getData('customer_id'));
    }

    /**
     * @see CustomerPrice::setCustomerId()
     */
    public function testGetCustomerId(): void
    {
        $expectedValue = 4;
        $this->customerPrice->setData('customer_id', $expectedValue);
        $this->assertEquals($expectedValue, $this->customerPrice->getCustomerId());
    }

    /**
     * @see CustomerPrice::setProductId()
     */
    public function testSetProductId(): void
    {
        $expectedValue = 5;
        $this->customerPrice->setProductId($expectedValue);
        $this->assertEquals($expectedValue, $this->customerPrice->getData('product_id'));
    }

    /**
     * @see CustomerPrice::setProductId()
     */
    public function testGetProductId(): void
    {
        $expectedValue = 6;
        $this->customerPrice->setData('product_id', $expectedValue);
        $this->assertEquals($expectedValue, $this->customerPrice->getProductId());
    }

    /**
     * @see CustomerPrice::setQty()
     */
    public function testSetQty(): void
    {
        $expectedValue = 7.1;
        $this->customerPrice->setQty($expectedValue);
        $this->assertEquals($expectedValue, $this->customerPrice->getData('qty'));
    }

    /**
     * @see CustomerPrice::setQty()
     */
    public function testGetQty(): void
    {
        $expectedValue = 8.2;
        $this->customerPrice->setData('qty', $expectedValue);
        $this->assertEquals($expectedValue, $this->customerPrice->getQty());
    }

    /**
     * @see CustomerPrice::setPrice()
     */
    public function testSetPrice(): void
    {
        $expectedValue = 9.1;
        $this->customerPrice->setPrice($expectedValue);
        $this->assertEquals($expectedValue, $this->customerPrice->getData('price'));
    }

    /**
     * @see CustomerPrice::setPrice()
     */
    public function testGetPrice(): void
    {
        $expectedValue = 10.2;
        $this->customerPrice->setData('price', $expectedValue);
        $this->assertEquals($expectedValue, $this->customerPrice->getPrice());
    }

    /**
     * @see CustomerPrice::setWebsiteId()
     */
    public function testSetWebsiteId(): void
    {
        $expectedValue = 11;
        $this->customerPrice->setWebsiteId($expectedValue);
        $this->assertEquals($expectedValue, $this->customerPrice->getData('website_id'));
    }

    /**
     * @see CustomerPrice::setWebsiteId()
     */
    public function testGetWebsiteId(): void
    {
        $expectedValue = 12;
        $this->customerPrice->setData('website_id', $expectedValue);
        $this->assertEquals($expectedValue, $this->customerPrice->getWebsiteId());
    }

    /**
     * @see CustomerPrice::setCreatedAt()
     */
    public function testSetCreatedAt(): void
    {
        $expectedValue = 'test13';
        $this->customerPrice->setCreatedAt($expectedValue);
        $this->assertEquals($expectedValue, $this->customerPrice->getData('created_at'));
    }

    /**
     * @see CustomerPrice::setCreatedAt()
     */
    public function testGetCreatedAt(): void
    {
        $expectedValue = 'test14';
        $this->customerPrice->setData('created_at', $expectedValue);
        $this->assertEquals($expectedValue, $this->customerPrice->getCreatedAt());
    }

    /**
     * @see CustomerPrice::getUpdatedAt()
     */
    public function testGetUpdatedAt(): void
    {
        $expectedValue = 'test15';
        $this->customerPrice->setUpdatedAt($expectedValue);
        $this->assertEquals($expectedValue, $this->customerPrice->getData('updated_at'));
    }

    /**
     * @see CustomerPrice::setUpdatedAt()
     */
    public function testSetUpdatedAt(): void
    {
        $expectedValue = 'test16';
        $this->customerPrice->setData('updated_at', $expectedValue);
        $this->assertEquals($expectedValue, $this->customerPrice->getUpdatedAt());
    }
}
