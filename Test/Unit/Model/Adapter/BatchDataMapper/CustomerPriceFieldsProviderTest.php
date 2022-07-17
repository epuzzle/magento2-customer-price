<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Test\Unit\Model\Adapter\BatchDataMapper;

use EPuzzle\CustomerPrice\Model\Adapter\BatchDataMapper\CustomerPriceFieldsProvider;
use EPuzzle\CustomerPrice\Model\Adapter\FieldMapper\Product\FieldProvider\FieldName\CustomerPriceFieldNameResolver;
use EPuzzle\CustomerPrice\Model\Customer\CustomerProviderInterface;
use EPuzzle\CustomerPrice\Model\Customer\GetScopeCustomerIdsGroupIds;
use EPuzzle\CustomerPrice\Model\ResourceModel\CustomerPrice;
use Magento\CatalogSearch\Model\Indexer\Fulltext\Action\DataProvider;
use Magento\Elasticsearch\Model\ResourceModel\Index;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @see CustomerPriceFieldsProvider
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class CustomerPriceFieldsProviderTest extends TestCase
{
    /**
     * @var CustomerPrice|MockObject
     */
    private CustomerPrice $resource;

    /**
     * @var Index|MockObject
     */
    private Index $priceResourceIndex;

    /**
     * @var DataProvider|MockObject
     */
    private DataProvider $dataProvider;

    /**
     * @var StoreManagerInterface|MockObject
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var GetScopeCustomerIdsGroupIds|MockObject
     */
    private GetScopeCustomerIdsGroupIds $getScopeCustomerIdsGroupIds;

    /**
     * @var CustomerPriceFieldNameResolver
     */
    private CustomerPriceFieldNameResolver $customerPriceFieldNameResolver;

    /**
     * @var CustomerPriceFieldsProvider
     */
    private CustomerPriceFieldsProvider $customerPriceFieldsProvider;

    /**
     * @inheritDoc
     *
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    protected function setUp(): void
    {
        $this->resource = $this->createMock(CustomerPrice::class);
        $this->priceResourceIndex = $this->createMock(Index::class);
        $this->dataProvider = $this->createMock(DataProvider::class);
        $this->storeManager = $this->createMock(StoreManagerInterface::class);
        $this->getScopeCustomerIdsGroupIds = $this->createMock(GetScopeCustomerIdsGroupIds::class);
        $customerProvider = $this->createMock(CustomerProviderInterface::class);
        $this->customerPriceFieldNameResolver = new CustomerPriceFieldNameResolver(
            $customerProvider
        );

        $this->customerPriceFieldsProvider = new CustomerPriceFieldsProvider(
            $this->resource,
            $this->priceResourceIndex,
            $this->dataProvider,
            $this->storeManager,
            $this->getScopeCustomerIdsGroupIds,
            $this->customerPriceFieldNameResolver
        );
    }

    /**
     * @dataProvider dataProvider
     * @param int $storeId
     * @param array $productIds
     * @param array $customerIdsGroupIds
     * @param string $attributeCode
     * @param array $expectedValue
     * @see CustomerPriceFieldsProvider::getFields()
     */
    public function testGetFields(
        int $storeId,
        array $productIds,
        array $customerIdsGroupIds,
        string $attributeCode,
        array $expectedValue
    ): void {
        $objectManager = new ObjectManager($this);
        $websiteId = $storeId;

        /** @var Store $store */
        $store = $objectManager->getObject(Store::class);
        $store->setWebsiteId($websiteId);
        $this->storeManager->expects($this->once())
            ->method('getStore')
            ->with($storeId)
            ->willReturn($store);
        $this->priceResourceIndex->expects($this->once())
            ->method('getPriceIndexData')
            ->with($productIds, $storeId)
            ->willReturn($this->getPriceIndexData($productIds, array_values($customerIdsGroupIds)));
        $this->dataProvider->expects($this->once())
            ->method('getSearchableAttribute')
            ->with($attributeCode)
            ->willReturn(true);
        $this->getScopeCustomerIdsGroupIds->expects($this->once())
            ->method('execute')
            ->with($websiteId)
            ->willReturn($customerIdsGroupIds);
        $this->resource->expects($this->once())
            ->method('getPriceIndexData')
            ->with($productIds, $websiteId)
            ->willReturn($this->getCustomerPriceIndexData($productIds, array_keys($customerIdsGroupIds)));

        $this->assertEquals(
            $expectedValue,
            $this->customerPriceFieldsProvider->getFields($productIds, $storeId)
        );
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            // [storeId/websiteId, productIds, customerIdsGroupIds, attributeCode, expectedValue]
            [
                1,
                [1,2,3],
                [
                    // [customerId, groupId]
                    1 => 1,
                    2 => 1,
                    3 => 1,
                ],
                'price',
                [
                    1 => [
                        'customer_price_1_1' => '10.000000',
                        'customer_price_1_2' => '10.000000',
                        'customer_price_1_3' => '10.000000'
                    ],
                    2 => [
                        'customer_price_1_1' => '10.000000',
                        'customer_price_1_2' => '10.000000',
                        'customer_price_1_3' => '10.000000'
                    ],
                    3 => [
                        'customer_price_1_1' => '10.000000',
                        'customer_price_1_2' => '10.000000',
                        'customer_price_1_3' => '10.000000'
                    ]
                ]
            ],
            [
                2,
                [4,5,6],
                [
                    // [customerId, groupId]
                    4 => 2,
                    5 => 2,
                    6 => 2,
                ],
                'price',
                [
                    4 => [
                        'customer_price_2_4' => '10.000000',
                        'customer_price_2_5' => '10.000000',
                        'customer_price_2_6' => '10.000000'
                    ],
                    5 => [
                        'customer_price_2_4' => '10.000000',
                        'customer_price_2_5' => '10.000000',
                        'customer_price_2_6' => '10.000000'
                    ],
                    6 => [
                        'customer_price_2_4' => '10.000000',
                        'customer_price_2_5' => '10.000000',
                        'customer_price_2_6' => '10.000000'
                    ]
                ]
            ],
            [
                3,
                [4,5,6],
                [
                    // [customerId, groupId]
                    4 => 3,
                    5 => 3,
                    6 => 3,
                ],
                'price',
                [
                    4 => [
                        'customer_price_3_4' => '10.000000',
                        'customer_price_3_5' => '10.000000',
                        'customer_price_3_6' => '10.000000'
                    ],
                    5 => [
                        'customer_price_3_4' => '10.000000',
                        'customer_price_3_5' => '10.000000',
                        'customer_price_3_6' => '10.000000'
                    ],
                    6 => [
                        'customer_price_3_4' => '10.000000',
                        'customer_price_3_5' => '10.000000',
                        'customer_price_3_6' => '10.000000'
                    ]
                ]
            ]
        ];
    }

    /**
     * Get customer price index data
     *
     * @param int[] $productIds
     * @param int[] $customerIds
     * @return array
     */
    private function getCustomerPriceIndexData(array $productIds, array $customerIds): array
    {
        $indexData = [];

        foreach ($productIds as $productId) {
            foreach ($customerIds as $customerId) {
                $indexData[$productId][$customerId] = 10;
            }
        }

        return $indexData;
    }

    /**
     * Get price index data
     *
     * @param int[] $productIds
     * @param int[] $groupIds
     * @return array
     */
    private function getPriceIndexData(array $productIds, array $groupIds): array
    {
        $indexData = [];

        $groupIds = array_unique($groupIds);
        foreach ($productIds as $productId) {
            foreach ($groupIds as $groupId) {
                $indexData[$productId][$groupId] = 20;
            }
        }

        return $indexData;
    }
}
