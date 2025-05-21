<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Pricing\Price;

use Exception;
use Magento\Framework\Pricing\Adjustment\CalculatorInterface;
use Magento\Framework\Pricing\Price\AbstractPrice;
use Magento\Framework\Pricing\Price\BasePriceProviderInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Pricing\SaleableInterface;
use Psr\Log\LoggerInterface;

/**
 * The default customer price model
 *
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
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly SaleableInterface $saleableItem,
        float $quantity,
        CalculatorInterface $calculator,
        PriceCurrencyInterface $priceCurrency,
        private readonly CustomerPrice\PriceCollectorProvider $priceCollectorProvider,
        private readonly LoggerInterface $logger
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
        if (null !== $this->value) {
            return $this->value;
        }
        try {
            $priceCollector = $this->priceCollectorProvider->get();
            $this->value = $priceCollector->collect($this->saleableItem, $this->quantity);
            $this->value = $this->value === null ? false : (float)$this->value;
        } catch (Exception $exception) {
            $this->logger->critical($exception);

            // exit: the customer price collector is not configured
            return $this->value = false;
        }

        return $this->value;
    }
}
