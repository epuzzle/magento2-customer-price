<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Test\Unit\Model\Adapter\FieldMapper\Product\FieldProvider;

use EPuzzle\CustomerPrice\Model\Adapter\FieldMapper\Product\FieldProvider\CustomerPriceField;
use EPuzzle\CustomerPrice\Model\Adapter\FieldMapper\Product\FieldProvider\FieldName\CustomerPriceFieldNameResolver;
use EPuzzle\CustomerPrice\Model\Customer\CustomerProviderInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\ResourceModel\Customer\Collection;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;
use Magento\Elasticsearch\Model\Adapter\FieldMapper\Product\FieldProvider\FieldType\ConverterInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @see CustomerPriceField
 */
class CustomerPriceFieldTest extends TestCase
{
    /**
     * @var Collection|MockObject
     */
    private Collection $customerCollection;

    /**
     * @var CustomerPriceField
     */
    private CustomerPriceField $customerPriceField;

    /**
     * @inheritDoc
     *
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    protected function setUp(): void
    {
        $this->customerCollection = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->customerCollection->expects($this->any())
            ->method('addFieldToSelect')
            ->willReturnSelf();
        $this->customerCollection->expects($this->any())
            ->method('addFieldToSelect')
            ->willReturnSelf();
        $this->customerCollection->expects($this->any())
            ->method('load')
            ->willReturnSelf();
        $customerCollectionFactory = $this->getMockBuilder(CollectionFactory::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['create'])
            ->getMock();
        $customerCollectionFactory->expects($this->any())
            ->method('create')
            ->willReturn($this->customerCollection);
        $customerProvider = $this->getMockBuilder(CustomerProviderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $customerPriceFieldNameResolver = new CustomerPriceFieldNameResolver($customerProvider);
        $fieldTypeConverter = $this->getMockBuilder(ConverterInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $fieldTypeConverter->expects($this->any())
            ->method('convert')
            ->with(ConverterInterface::INTERNAL_DATA_TYPE_FLOAT)
            ->willReturn('double');
        $this->customerPriceField = new CustomerPriceField(
            $fieldTypeConverter,
            $customerCollectionFactory,
            $customerPriceFieldNameResolver
        );
    }

    /**
     * @dataProvider dataProvider
     * @param array $customers
     * @param array $expectedValue
     * @see CustomerPriceField::getFields()
     */
    public function testGetFields(array $customers, array $expectedValue): void
    {
        $this->customerCollection->expects($this->once())
            ->method('getItems')
            ->willReturn($this->createCustomers($customers));
        $this->assertEquals($expectedValue, $this->customerPriceField->getFields());
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            // [customers, expectedValue]
            [
                [
                    ['customerId' => 1, 'websiteId' => 1],
                    ['customerId' => 2, 'websiteId' => 1],
                    ['customerId' => 3, 'websiteId' => 1]
                ],
                [
                    'customer_price_1_1' => ['type' => 'double', 'store' => true],
                    'customer_price_1_2' => ['type' => 'double', 'store' => true],
                    'customer_price_1_3' => ['type' => 'double', 'store' => true],
                ]
            ],
            [
                [
                    ['customerId' => 4, 'websiteId' => 2],
                    ['customerId' => 5, 'websiteId' => 2],
                    ['customerId' => 6, 'websiteId' => 2]
                ],
                [
                    'customer_price_2_4' => ['type' => 'double', 'store' => true],
                    'customer_price_2_5' => ['type' => 'double', 'store' => true],
                    'customer_price_2_6' => ['type' => 'double', 'store' => true],
                ]
            ],
            [
                [
                    ['customerId' => 7, 'websiteId' => 3],
                    ['customerId' => 8, 'websiteId' => 3],
                    ['customerId' => 9, 'websiteId' => 3]
                ],
                [
                    'customer_price_3_7' => ['type' => 'double', 'store' => true],
                    'customer_price_3_8' => ['type' => 'double', 'store' => true],
                    'customer_price_3_9' => ['type' => 'double', 'store' => true],
                ]
            ]
        ];
    }

    /**
     * @param array $items
     * @return array
     */
    private function createCustomers(array $items): array
    {
        $customers = [];
        foreach ($items as $item) {
            $customer = $this->getMockBuilder(CustomerInterface::class)
                ->disableOriginalConstructor()
                ->getMock();
            $customer->expects($this->once())
                ->method('getId')
                ->willReturn($item['customerId']);
            $customer->expects($this->once())
                ->method('getWebsiteId')
                ->willReturn($item['websiteId']);
            $customers[] = $customer;
        }
        return $customers;
    }
}
