<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Model\Adapter\FieldMapper\Product\FieldProvider;

use EPuzzle\CustomerPrice\Model\Adapter\FieldMapper\Product\FieldProvider\FieldName\CustomerPriceFieldNameResolver;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;
use Magento\Elasticsearch\Model\Adapter\FieldMapper\Product\FieldProvider\FieldType\ConverterInterface;
use Magento\Elasticsearch\Model\Adapter\FieldMapper\Product\FieldProviderInterface;

/**
 * Provide customer price fields for product
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class CustomerPriceField implements FieldProviderInterface
{
    /**
     * CustomerPriceField
     *
     * @param ConverterInterface $fieldTypeConverter
     * @param CollectionFactory $customerCollectionFactory
     * @param CustomerPriceFieldNameResolver $customerPriceFieldNameResolver
     */
    public function __construct(
        private readonly ConverterInterface $fieldTypeConverter,
        private readonly CollectionFactory $customerCollectionFactory,
        private readonly CustomerPriceFieldNameResolver $customerPriceFieldNameResolver
    ) {
    }

    /**
     * @inheritdoc
     */
    public function getFields(array $context = []): array
    {
        $fields = [];
        $collection = $this->customerCollectionFactory->create();
        $collection->addFieldToSelect('entity_id');
        $collection->addFieldToSelect('website_id');
        if (isset($context['websiteId'])) {
            $collection->addFieldToFilter('website_id', $context['websiteId']);
        }
        $type = $this->fieldTypeConverter->convert(ConverterInterface::INTERNAL_DATA_TYPE_FLOAT);
        /** @var CustomerInterface $customer */
        foreach ($collection->getItems() as $customer) {
            $fieldName = $this->customerPriceFieldNameResolver->resolve(
                ['websiteId' => $customer->getWebsiteId(), 'customerId' => $customer->getId()]
            );
            $fields[$fieldName] = ['type' => $type, 'store' => true];
        }

        return $fields;
    }
}
