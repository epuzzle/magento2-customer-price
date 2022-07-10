<?php

/**
 * Customer prices for Magento 2 platform
 *
 * @author Dmytro Kaplin <dkaplin1994@gmail.com>
 * @license https://github.com/epuzzle/magento2-customer-price/blob/main/LICENSE
 */

declare(strict_types=1);

namespace ePuzzle\CustomerPrice\Plugin\Framework\Pricing\Render\PriceBox;

use ePuzzle\CustomerPrice\Model\Customer\CustomerProviderInterface;
use ePuzzle\CustomerPrice\Model\Customer\ExistingCustomerPriceByStrategy;
use Magento\Framework\Pricing\Render\PriceBox;

/**
 * Adding additional cache tags to the price box
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class AddCacheTagsToPriceBox
{
    private CustomerProviderInterface $customerProvider;
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
        if (($customerId = $this->customerProvider->getCustomerId())
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
