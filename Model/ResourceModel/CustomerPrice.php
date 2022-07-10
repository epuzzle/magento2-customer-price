<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * The resource of the customer price entity
 *
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 */
class CustomerPrice extends AbstractDb
{
    public const TABLE_NAME = 'epuzzle_customer_price';
    public const PK = 'item_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, self::PK);
    }

    /**
     * Get main table
     *
     * @return string
     */
    public function getMainTable(): string
    {
        return $this->getTable(self::TABLE_NAME);
    }

    /**
     * Get price index data for scope (website)
     *
     * @param int[] $productIds
     * @param int $websiteId
     * @return array
     */
    public function getPriceIndexData(array $productIds, int $websiteId): array
    {
        $select = $this->getConnection()->select();
        $select->from(['main' => $this->getMainTable()], ['product_id', 'customer_id', 'price']);
        $select->joinInner(
            ['customer' => $this->getTable('customer_entity')],
            'customer.entity_id = main.customer_id AND customer.website_id = ' . $websiteId,
            ''
        );
        $select->where('main.product_id IN (?)', $productIds);
        $select->where('main.qty = 1');
        $select->where('main.website_id = ?', $websiteId);

        $customerPrices = [];
        foreach ($this->getConnection()->fetchAssoc($select) as $priceRow) {
            $customerPrices[$priceRow['product_id']][$priceRow['customer_id']] = $priceRow['price'];
        }

        return $customerPrices;
    }
}
