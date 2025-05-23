<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Pricing\Price;

use Magento\Framework\Pricing\Adjustment\CalculatorInterface;
use Magento\Framework\Pricing\Price\AbstractPrice;
use Magento\Framework\Pricing\Price\BasePriceProviderInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Pricing\SaleableInterface;

/**
 * The default customer price model
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class CustomerPrice extends AbstractPrice implements BasePriceProviderInterface
{
    /**
     * CustomerPrice
     *
     * @param SaleableInterface $saleableItem
     * @param float $quantity
     * @param CalculatorInterface $calculator
     * @param PriceCurrencyInterface $priceCurrency
     * @param CustomerPrice\PriceCollectorProvider $priceCollectorProvider
     */
    public function __construct(
        private readonly SaleableInterface $saleableItem,
        float $quantity,
        CalculatorInterface $calculator,
        PriceCurrencyInterface $priceCurrency,
        private readonly CustomerPrice\PriceCollectorProvider $priceCollectorProvider
    ) {
        parent::__construct(
            $saleableItem,
            $quantity,
            $calculator,
            $priceCurrency
        );
    }

    /**
     * @inheritDoc
     */
    public function getValue()
    {
        if (null === $this->value) {
            $this->value = $this->priceCollectorProvider->getWithNull()
                ?->collect($this->saleableItem, $this->quantity) ?? null;
            $this->value = $this->value === null ? false : (float)$this->value;
        }

        return $this->value;
    }
}
