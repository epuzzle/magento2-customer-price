<?php

/**
 * Customer prices for Magento 2 platform
 *
 * @author Dmytro Kaplin <dkaplin1994@gmail.com>
 * @license https://github.com/epuzzle/magento2-customer-price/blob/main/LICENSE
 */

declare(strict_types=1);

namespace ePuzzle\CustomerPrice\Plugin\Model\Adapter\FieldMapper\FieldMapperResolver;

use ePuzzle\CustomerPrice\Model\Adapter\FieldMapper\Product\FieldProvider\FieldName\CustomerPriceFieldNameResolver;
use ePuzzle\CustomerPrice\Model\Customer\CustomerProviderInterface;
use Magento\Elasticsearch\Model\Adapter\FieldMapperInterface;

/**
 * Updating the price field to the customer price field
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class UpdatePriceFieldToCustomerPriceField
{
    private CustomerProviderInterface $customerProvider;
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
