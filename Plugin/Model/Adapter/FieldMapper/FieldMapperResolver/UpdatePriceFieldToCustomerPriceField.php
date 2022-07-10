<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Plugin\Model\Adapter\FieldMapper\FieldMapperResolver;

use EPuzzle\CustomerPrice\Model\Adapter\FieldMapper\Product\FieldProvider\FieldName\CustomerPriceFieldNameResolver;
use EPuzzle\CustomerPrice\Model\Customer\CustomerProviderInterface;
use Magento\Elasticsearch\Model\Adapter\FieldMapperInterface;

/**
 * Updating the price field to the customer price field
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class UpdatePriceFieldToCustomerPriceField
{
    /**
     * @var CustomerProviderInterface
     */
    private CustomerProviderInterface $customerProvider;

    /**
     * @var CustomerPriceFieldNameResolver
     */
    private CustomerPriceFieldNameResolver $customerPriceFieldNameResolver;

    /**
     * UpdatePriceFieldToCustomerPriceField
     *
     * @param CustomerProviderInterface $customerProvider
     * @param CustomerPriceFieldNameResolver $customerPriceFieldNameResolver
     */
    public function __construct(
        CustomerProviderInterface $customerProvider,
        CustomerPriceFieldNameResolver $customerPriceFieldNameResolver
    ) {
        $this->customerProvider = $customerProvider;
        $this->customerPriceFieldNameResolver = $customerPriceFieldNameResolver;
    }

    /**
     * Updating the price field to the customer price field
     *
     * @param FieldMapperInterface $fieldMapper
     * @param string $fieldName
     * @param string $attributeCode
     * @return string
     * @see FieldMapperInterface::getFieldName()
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetFieldName(
        FieldMapperInterface $fieldMapper,
        string $fieldName,
        string $attributeCode
    ): string {
        if ('price' === $attributeCode && $this->customerProvider->getCustomerId()) {
            return $this->customerPriceFieldNameResolver->resolve(
                [
                    'websiteId' => $this->customerProvider->getWebsiteId(),
                    'customerId' => $this->customerProvider->getCustomerId()
                ]
            );
        }
        return $fieldName;
    }
}
