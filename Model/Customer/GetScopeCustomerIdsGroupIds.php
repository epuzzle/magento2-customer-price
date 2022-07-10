<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Model\Customer;

use Magento\Framework\App\ResourceConnection;

/**
 * Getting information about customer by scope (website)
 */
class GetScopeCustomerIdsGroupIds
{
    /**
     * @var ResourceConnection
     */
    private ResourceConnection $resourceConnection;

    /**
     * @var array[]
     */
    private array $cache = [];

    /**
     * GetScopeCustomerIdsGroupIds
     *
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        ResourceConnection $resourceConnection
    ) {
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * Getting information about customer by scope (website)
     *
     * @param int $websiteId
     * @return array
     */
    public function execute(int $websiteId): array
    {
        if (isset($this->cache[$websiteId])) {
            return $this->cache[$websiteId];
        }

        $select = $this->resourceConnection->getConnection()->select();
        $select->from($this->resourceConnection->getTableName('customer_entity'), ['entity_id', 'group_id']);
        $select->where('website_id = ?', $websiteId);
        return $this->cache[$websiteId] = $this->resourceConnection->getConnection()->fetchPairs($select);
    }
}
