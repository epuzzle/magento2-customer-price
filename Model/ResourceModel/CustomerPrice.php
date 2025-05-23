<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * The resource of the customer price entity
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
        // selects all configurable parent products
        $relatedProductIds = $this->getRelatedProductIds($productIds);
        $productIds = array_merge($productIds, array_keys($relatedProductIds));
        // selects all customer prices for provided product IDs
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
        $select->order('main.product_id ASC');
        $customerPrices = [];
        foreach ($this->getConnection()->fetchAssoc($select) as $priceRow) {
            $customerPrices[$priceRow['product_id']][$priceRow['customer_id']] = $priceRow['price'];
            if (isset($relatedProductIds[$priceRow['product_id']])) {
                $parentId = $relatedProductIds[$priceRow['product_id']];
                $customerPrices[$parentId][$priceRow['customer_id']] = $priceRow['price'];
            }
        }

        return $customerPrices;
    }

    /**
     * Get the list of related product IDs
     *
     * @param array $productIds
     * @return array
     */
    public function getRelatedProductIds(array $productIds): array
    {
        // selects all parent products for grouped/configurable products
        $select = $this->getConnection()->select();
        $select->from($this->getTable('catalog_product_relation'), ['parent_id', 'child_id']);
        $select->where('parent_id IN (?)', $productIds);
        $relatedProductIds = [];
        foreach ($this->getConnection()->fetchAll($select) ?: [] as $item) {
            $relatedProductIds[$item['child_id']] = $item['parent_id'];
        }

        return $relatedProductIds;
    }
}
