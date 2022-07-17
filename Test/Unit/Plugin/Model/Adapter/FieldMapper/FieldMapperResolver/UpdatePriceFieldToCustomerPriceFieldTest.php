<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Test\Unit\Plugin\Model\Adapter\FieldMapper\FieldMapperResolver;

use EPuzzle\CustomerPrice\Model\Adapter\FieldMapper\Product\FieldProvider\FieldName\CustomerPriceFieldNameResolver;
use EPuzzle\CustomerPrice\Model\Customer\CustomerProviderInterface;
use EPuzzle\CustomerPrice\Plugin\Model\Adapter\FieldMapper\FieldMapperResolver\UpdatePriceFieldToCustomerPriceField;
use Magento\Elasticsearch\Model\Adapter\FieldMapperInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @see UpdatePriceFieldToCustomerPriceField
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class UpdatePriceFieldToCustomerPriceFieldTest extends TestCase
{
    /**
     * @var CustomerProviderInterface|MockObject
     */
    private CustomerProviderInterface $customerProvider;

    /**
     * @var FieldMapperInterface|MockObject
     */
    private FieldMapperInterface $fieldMapper;

    /**
     * @var UpdatePriceFieldToCustomerPriceField
     */
    private UpdatePriceFieldToCustomerPriceField $updatePriceFieldToCustomerPriceField;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->customerProvider = $this->createMock(CustomerProviderInterface::class);
        $customerPriceFieldNameResolver = new CustomerPriceFieldNameResolver($this->customerProvider);
        $this->fieldMapper = $this->createMock(FieldMapperInterface::class);
        $this->updatePriceFieldToCustomerPriceField = new UpdatePriceFieldToCustomerPriceField(
            $this->customerProvider,
            $customerPriceFieldNameResolver
        );
    }

    /**
     * @dataProvider dataProvider
     * @param int $customerId
     * @param int $websiteId
     * @param string $attributeCode
     * @param string $fieldName
     * @param string $expectedValue
     * @see UpdatePriceFieldToCustomerPriceField::afterGetFieldName()
     */
    public function testAfterGetFieldName(
        int $customerId,
        int $websiteId,
        string $attributeCode,
        string $fieldName,
        string $expectedValue
    ): void {
        $this->customerProvider->expects($this->any())
            ->method('getCustomerId')
            ->willReturn($customerId);
        $this->customerProvider->expects($this->any())
            ->method('getWebsiteId')
            ->willReturn($websiteId);

        $this->assertEquals(
            $expectedValue,
            $this->updatePriceFieldToCustomerPriceField->afterGetFieldName(
                $this->fieldMapper,
                $fieldName,
                $attributeCode
            )
        );
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            // [customerId, websiteId, attributeCode, fieldName, expectedValue]
            [1, 1, 'price', 'price_1_1', 'customer_price_1_1'],
            [2, 1, 'price', 'price_1_2', 'customer_price_1_2'],
            [3, 2, 'price', 'price_1_2', 'customer_price_2_3'],
            [4, 3, 'price', 'price_1_2', 'customer_price_3_4'],
            [1, 1, 'attribute1', 'attribute1_1_1', 'attribute1_1_1'],
            [2, 1, 'attribute2', 'attribute2_1_2', 'attribute2_1_2'],
            [3, 2, 'attribute3', 'attribute3_2_3', 'attribute3_2_3'],
            [4, 3, 'attribute4', 'attribute4_3_4', 'attribute4_3_4']
        ];
    }
}
