<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Model\Customer;

use Magento\Framework\App\ResourceConnection;

/**
 * Is exist the customer price by the strategy? The strategy is the column name
 */
class ExistingCustomerPriceByStrategy
{
    public const STRATEGY_CUSTOMER = 'customer_id';
    public const STRATEGY_PRODUCT = 'product_id';

    /**
     * @var ResourceConnection
     */
    private ResourceConnection $resourceConnection;

    /**
     * @var boolean[]
     */
    private array $cache = [];

    /**
     * ExistingCustomerPriceByStrategy
     *
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        ResourceConnection $resourceConnection
    ) {
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * Is exist the customer price by the strategy? The strategy is the column name
     *
     * @param int $value
     * @param string $strategy
     * @return bool
     */
    public function execute(int $value, string $strategy): bool
    {
        if (!in_array($strategy, [self::STRATEGY_CUSTOMER, self::STRATEGY_PRODUCT])) {
            $strategy = self::STRATEGY_CUSTOMER;
        }

        $cacheKey = $strategy . $value;
        if (isset($this->cache[$cacheKey])) {
            return $this->cache[$cacheKey];
        }

        $select = $this->resourceConnection->getConnection()->select();
        $select->from($this->resourceConnection->getTableName('epuzzle_customer_price'), 'COUNT(*)');
        $select->where($strategy . ' = ?', $value);
        $size = $this->resourceConnection->getConnection()->fetchOne($select);
        return $this->cache[$cacheKey] = $size > 0;
    }
}
