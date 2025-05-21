<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Pricing\Price\CustomerPrice;

use Magento\Framework\Pricing\Price\BasePriceProviderInterface;
use Magento\Framework\Pricing\SaleableInterface;

/**
 * Used to collect the customer price for the given product and quantity
 */
interface PriceCollectorInterface extends BasePriceProviderInterface
{
    public const PRICE_CODE = 'epCustomerPrice';

    /**
     * Collects the customer price for the given product and quantity
     *
     * @param SaleableInterface $product
     * @param float $quantity
     * @return float|null
     */
    public function collect(SaleableInterface $product, float $quantity = 1.0): ?float;

    /**
     * Is the customer price used in search engines?
     *
     * @return bool
     */
    public function isSearchable(): bool;
}
