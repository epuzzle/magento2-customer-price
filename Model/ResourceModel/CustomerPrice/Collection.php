<?php

/**
 * Customer prices for Magento 2 platform
 *
 * @author Dmytro Kaplin <dkaplin1994@gmail.com>
 * @license https://github.com/epuzzle/magento2-customer-price/blob/main/LICENSE
 */

declare(strict_types=1);

namespace ePuzzle\CustomerPrice\Model\ResourceModel\CustomerPrice;

use ePuzzle\CustomerPrice\Model\CustomerPrice;
use ePuzzle\CustomerPrice\Model\ResourceModel\CustomerPrice as CustomerPriceResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * The collection of customer price entities
 *
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 */
class Collection extends AbstractCollection
{
    protected $_idFieldName = CustomerPriceResource::PK;
    protected $_eventPrefix = 'ep_customer_price_collection';
    protected $_eventObject = 'customer_price_collection';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(CustomerPrice::class, CustomerPriceResource::class);
    }
}
