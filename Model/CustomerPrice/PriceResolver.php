<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Model\CustomerPrice;

use EPuzzle\CustomerPrice\Model\ResourceModel\CustomerPrice;

/**
 * Resolving the customer price for the product
 */
class PriceResolver
{
    /**
     * @var CustomerPrice
     */
    private CustomerPrice $resource;

    /**
     * @var float|null[]
     */
    private $cache = [];

    /**
     * PriceResolver
     *
     * @param CustomerPrice $resource
     */
    public function __construct(
        CustomerPrice $resource
    ) {
        $this->resource = $resource;
    }

    /**
     * Resolving the customer price for the product
     *
     * @param int $customerId
     * @param int $websiteId
     * @param int $productId
     * @param float $qty
     * @return float|null
     */
    public function resolve(
        int $customerId,
        int $websiteId,
        int $productId,
        float $qty
    ): ?float {
        $cacheKey = $customerId . $productId . $qty;
        if (isset($this->cache[$cacheKey])) {
            return $this->cache[$cacheKey];
        }

        $select = $this->resource->getConnection()->select();
        $select->from($this->resource->getMainTable(), 'price');
        $select->where('product_id = ?', $productId);
        $select->where('customer_id = ?', $customerId);
        $select->where('qty <= ?', max($qty, 1));
        $select->where('qty != ?', 0);
//        $select->where('website_id = ?', $websiteId);
        $select->order('qty DESC');
        $select->limit(1);
        $price = (string)$this->resource->getConnection()->fetchOne($select);
        return $this->cache[$cacheKey] = $price !== '' ? (float)$price : null;
    }
}
