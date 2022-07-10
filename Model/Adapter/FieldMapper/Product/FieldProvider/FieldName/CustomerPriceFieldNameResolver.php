<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Model\Adapter\FieldMapper\Product\FieldProvider\FieldName;

use EPuzzle\CustomerPrice\Model\Customer\CustomerProviderInterface;

/**
 * Resolving field name for the customer price
 */
class CustomerPriceFieldNameResolver
{
    /**
     * @var CustomerProviderInterface
     */
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
