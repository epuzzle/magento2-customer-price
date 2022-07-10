<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Pricing\Price;

use EPuzzle\CustomerPrice\Model\Customer\CustomerProviderInterface;
use EPuzzle\CustomerPrice\Model\CustomerPrice\PriceResolver;
use Magento\Catalog\Model\Product;
use Magento\Framework\Pricing\Adjustment\CalculatorInterface;
use Magento\Framework\Pricing\Price\AbstractPrice;
use Magento\Framework\Pricing\Price\BasePriceProviderInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Pricing\SaleableInterface;

/**
 * The customer price model
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class CustomerPrice extends AbstractPrice implements BasePriceProviderInterface
{
    public const PRICE_CODE = 'epCustomerPrice';

    /**
     * @var CustomerProviderInterface
     */
    private CustomerProviderInterface $customerProvider;

    /**
     * @var PriceResolver
     */
    private PriceResolver $customerPriceResolver;

    /**
     * CustomerPrice
     *
     * @param SaleableInterface $saleableItem
     * @param float $quantity
     * @param CalculatorInterface $calculator
     * @param PriceCurrencyInterface $priceCurrency
     * @param CustomerProviderInterface $customerProvider
     * @param PriceResolver $customerPriceResolver
     */
    public function __construct(
        SaleableInterface $saleableItem,
        float $quantity,
        CalculatorInterface $calculator,
        PriceCurrencyInterface $priceCurrency,
        CustomerProviderInterface $customerProvider,
        PriceResolver $customerPriceResolver
    ) {
        parent::__construct($saleableItem, $quantity, $calculator, $priceCurrency);

        $this->customerProvider = $customerProvider;
        $this->customerPriceResolver = $customerPriceResolver;
    }

    /**
     * Returns the customer price
     *
     * @return float|boolean
     */
    public function getValue()
    {
        if (null !== $this->value) {
            return $this->value;
        }

        /** @var Product $product */
        $product = $this->getProduct();
        if ($product->hasData(self::PRICE_CODE)) {
            return $this->value = (float)$product->getData(self::PRICE_CODE);
        }

        $customerId = $this->customerProvider->getCustomerId();
        if (!$customerId) {
            return $this->value = false;
        }

        $price = $this->customerPriceResolver->resolve(
            $customerId,
            $this->customerProvider->getWebsiteId(),
            (int)$product->getId(),
            (float)$this->getQuantity()
        );

        if ($price) {
            $this->value = $this->priceCurrency->convertAndRound($price);
            $product->setData(Product::PRICE, $price);
        }

        return $this->value = $this->value ? (float)$this->value : false;
    }
}
