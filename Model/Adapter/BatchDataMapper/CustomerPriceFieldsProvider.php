<?php

/**
 * Customer prices for Magento 2 platform
 *
 * @author Dmytro Kaplin <dkaplin1994@gmail.com>
 * @license https://github.com/epuzzle/magento2-customer-price/blob/main/LICENSE
 */

declare(strict_types=1);

namespace ePuzzle\CustomerPrice\Model\Adapter\BatchDataMapper;

use ePuzzle\CustomerPrice\Model\Adapter\FieldMapper\Product\FieldProvider\FieldName\CustomerPriceFieldNameResolver;
use ePuzzle\CustomerPrice\Model\Customer\GetScopeCustomerIdsGroupIds;
use ePuzzle\CustomerPrice\Model\ResourceModel\CustomerPrice;
use Magento\AdvancedSearch\Model\Adapter\DataMapper\AdditionalFieldsProviderInterface;
use Magento\CatalogSearch\Model\Indexer\Fulltext\Action\DataProvider;
use Magento\Elasticsearch\Model\ResourceModel\Index;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Provide data mapping for customer price fields
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class CustomerPriceFieldsProvider implements AdditionalFieldsProviderInterface
{
    private CustomerPrice $resource;
    private Index $priceResourceIndex;
    private DataProvider $dataProvider;
    private StoreManagerInterface $storeManager;
    private GetScopeCustomerIdsGroupIds $getScopeCustomerIdsGroupIds;
    private CustomerPriceFieldNameResolver $customerPriceFieldNameResolver;

    /**
     * CustomerPriceFieldsProvider
     *
     * @param CustomerPrice $resource
     * @param Index $priceResourceIndex
     * @param DataProvider $dataProvider
     * @param StoreManagerInterface $storeManager
     * @param GetScopeCustomerIdsGroupIds $getScopeCustomerIdsGroupIds
     * @param CustomerPriceFieldNameResolver $customerPriceFieldNameResolver
     */
    public function __construct(
        CustomerPrice $resource,
        Index $priceResourceIndex,
        DataProvider $dataProvider,
        StoreManagerInterface $storeManager,
        GetScopeCustomerIdsGroupIds $getScopeCustomerIdsGroupIds,
        CustomerPriceFieldNameResolver $customerPriceFieldNameResolver
    ) {
        $this->resource = $resource;
        $this->priceResourceIndex = $priceResourceIndex;
        $this->dataProvider = $dataProvider;
        $this->storeManager = $storeManager;
        $this->getScopeCustomerIdsGroupIds = $getScopeCustomerIdsGroupIds;
        $this->customerPriceFieldNameResolver = $customerPriceFieldNameResolver;
    }

    /**
     * @inheritdoc
     */
    public function getFields(array $productIds, $storeId): array
    {
        $fields = [];
        if (!$this->dataProvider->getSearchableAttribute('price')) {
            return $fields;
        }

        $websiteId = (int)$this->storeManager->getStore($storeId)->getWebsiteId();
        $priceData = $this->priceResourceIndex->getPriceIndexData($productIds, $storeId);
        $customerIdsGroupIds = $this->getScopeCustomerIdsGroupIds->execute($websiteId);
        $customerPrices = $this->resource->getPriceIndexData($productIds, $websiteId);

        // generate fields for search
        foreach ($productIds as $productId) {
            if (!isset($priceData[$productId])) {
                continue;
            }

            $fields[$productId] = $this->getProductPriceData(
                $productId,
                $websiteId,
                $customerIdsGroupIds,
                $customerPrices,
                $priceData
            );
        }

        return $fields;
    }

    /**
     * Get product customer price data
     *
     * @param int $productId
     * @param int $websiteId
     * @param array $customerIdsGroupIds
     * @param array $customerPrices
     * @param array $priceData
     * @return array
     */
    private function getProductPriceData(
        int $productId,
        int $websiteId,
        array $customerIdsGroupIds,
        array $customerPrices,
        array $priceData
    ): array {
        $result = [];
        foreach ($customerIdsGroupIds as $customerId => $groupId) {
            $result[
                $this->customerPriceFieldNameResolver->resolve(
                    ['websiteId' => $websiteId, 'customerId' => $customerId]
                )
            ] = sprintf('%F', $customerPrices[$productId][$customerId] ?? $priceData[$productId][$groupId]);
        }
        return $result;
    }
}
