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
     * @var CustomerProviderInterface
     */
    private CustomerProviderInterface $customerProvider;

    /**
     * @var ExistingCustomerPriceByStrategy
     */
    private ExistingCustomerPriceByStrategy $existingCustomerPriceByStrategy;

    /**
     * AddCacheTagsToPriceBox
     *
     * @param CustomerProviderInterface $customerProvider
     * @param ExistingCustomerPriceByStrategy $existingCustomerPriceByStrategy
     */
    public function __construct(
        CustomerProviderInterface $customerProvider,
        ExistingCustomerPriceByStrategy $existingCustomerPriceByStrategy
    ) {
        $this->customerProvider = $customerProvider;
        $this->existingCustomerPriceByStrategy = $existingCustomerPriceByStrategy;
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
