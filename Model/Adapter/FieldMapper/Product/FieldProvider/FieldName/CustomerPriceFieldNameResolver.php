<?php

/**
 * Customer prices for Magento 2 platform
 *
 * @author Dmytro Kaplin <dkaplin1994@gmail.com>
 * @license https://github.com/epuzzle/magento2-customer-price/blob/main/LICENSE
 */

declare(strict_types=1);

namespace ePuzzle\CustomerPrice\Model\Adapter\FieldMapper\Product\FieldProvider\FieldName;

use ePuzzle\CustomerPrice\Model\Customer\CustomerProviderInterface;

/**
 * Resolving field name for the customer price
 */
class CustomerPriceFieldNameResolver
{
    private CustomerProviderInterface $customerProvider;

    /**
     * CustomerPriceFieldNameResolver
     *
     * @param CustomerProviderInterface $customerProvider
     */
    public function __construct(
        CustomerProviderInterface $customerProvider
    ) {
        $this->customerProvider = $customerProvider;
    }

    /**
     * Resolving field name for the customer price
     *
     * @param array $context
     * @return string
     */
    public function resolve(array $context = []): string
    {
        $websiteId = $context['websiteId'] ?? $this->customerProvider->getWebsiteId();
        $customerId = $context['customerId'] ?? $this->customerProvider->getCustomerId();
        return "customer_price_{$websiteId}_{$customerId}";
    }
}
