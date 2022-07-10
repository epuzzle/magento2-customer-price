<?php

/**
 * Customer prices for Magento 2 platform
 *
 * @author Dmytro Kaplin <dkaplin1994@gmail.com>
 * @license https://github.com/epuzzle/magento2-customer-price/blob/main/LICENSE
 */

declare(strict_types=1);

namespace ePuzzle\CustomerPrice\Model;

use ePuzzle\CustomerPrice\Api\Data\CustomerPriceInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * The model of the customer price entity
 *
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 */
class CustomerPrice extends AbstractModel implements CustomerPriceInterface
{
    protected $_cacheTag = 'ep_customer_price';
    protected $_eventPrefix = 'ep_customer_price';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\CustomerPrice::class);
        $this->setIdFieldName(ResourceModel\CustomerPrice::PK);
    }

    /**
     * @inheritDoc
     */
    public function beforeSave()
    {
        if ($this->hasDataChanges()) {
            $this->setData('updated_at');
        }
    }

    /**
     * @inheritDoc
     */
    public function getItemId(): int
    {
        return (int)$this->getData(ResourceModel\CustomerPrice::PK);
    }

    /**
     * @inheritDoc
     */
    public function setItemId(int $value): void
    {
        $this->setData(ResourceModel\CustomerPrice::PK, $value);
    }

    /**
     * @inheritDoc
     */
    public function getProductId(): int
    {
        return (int)$this->getData('product_id');
    }

    /**
     * @inheritDoc
     */
    public function setProductId(int $value): void
    {
        $this->setData('product_id', $value);
    }

    /**
     * @inheritDoc
     */
    public function getCustomerId(): int
    {
        return (int)$this->getData('customer_id');
    }

    /**
     * @inheritDoc
     */
    public function setCustomerId(int $value): void
    {
        $this->setData('customer_id', $value);
    }

    /**
     * @inheritDoc
     */
    public function getPrice(): float
    {
        return (float)$this->getData('price');
    }

    /**
     * @inheritDoc
     */
    public function setPrice(float $value): void
    {
        $this->setData('price', $value);
    }

    /**
     * @inheritDoc
     */
    public function getQty(): float
    {
        return (float)$this->getData('qty');
    }

    /**
     * @inheritDoc
     */
    public function setQty(float $value): void
    {
        $this->setData('qty', $value);
    }

    /**
     * @inheritDoc
     */
    public function getWebsiteId(): int
    {
        return (int)$this->getData('website_id');
    }

    /**
     * @inheritDoc
     */
    public function setWebsiteId(int $value): void
    {
        $this->setData('website_id', $value);
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt(): string
    {
        return (string)$this->getData('created_at');
    }

    /**
     * @inheritDoc
     */
    public function setCreatedAt(string $value): void
    {
        $this->setData('created_at', $value);
    }

    /**
     * @inheritDoc
     */
    public function getUpdatedAt(): string
    {
        return (string)$this->getData('updated_at');
    }

    /**
     * @inheritDoc
     */
    public function setUpdatedAt(string $value): void
    {
        $this->setData('updated_at', $value);
    }
}
