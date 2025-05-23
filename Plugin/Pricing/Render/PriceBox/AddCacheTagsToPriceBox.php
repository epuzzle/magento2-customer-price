<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Plugin\Pricing\Render\PriceBox;

use EPuzzle\CustomerPrice\Model\Customer\CustomerProviderInterface;
use EPuzzle\CustomerPrice\Model\Customer\ExistingCustomerPriceByStrategy;
use Magento\Framework\Pricing\Render\PriceBox;

/**
 * Adding additional cache tags to the price box
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class AddCacheTagsToPriceBox
{
    /**
     * AddCacheTagsToPriceBox
     *
     * @param CustomerProviderInterface $customerProvider
     * @param ExistingCustomerPriceByStrategy $existingCustomerPriceByStrategy
     */
    public function __construct(
        private readonly CustomerProviderInterface $customerProvider,
        private readonly ExistingCustomerPriceByStrategy $existingCustomerPriceByStrategy
    ) {
    }

    /**
     * Adding additional cache tags to the price box
     *
     * @param PriceBox $priceBox
     * @param string $cacheKey
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetCacheKey(PriceBox $priceBox, string $cacheKey): string
    {
        $customerId = $this->customerProvider->getCustomerId();
        if ($customerId
            && $this->existingCustomerPriceByStrategy->execute(
                (int)$customerId,
                ExistingCustomerPriceByStrategy::STRATEGY_CUSTOMER
            )
        ) {
            return implode('-', [$cacheKey, $customerId]);
        }

        return $cacheKey;
    }
}
