<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Model\ResourceModel\CustomerPrice;

use EPuzzle\CustomerPrice\Model\CustomerPrice;
use EPuzzle\CustomerPrice\Model\ResourceModel\CustomerPrice as CustomerPriceResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * The collection of customer price entities
 *
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = CustomerPriceResource::PK;

    /**
     * @var string
     */
    protected $_eventPrefix = 'epuzzle_customer_price_collection';

    /**
     * @var string
     */
    protected $_eventObject = 'customer_price_collection';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(CustomerPrice::class, CustomerPriceResource::class);
    }
}
