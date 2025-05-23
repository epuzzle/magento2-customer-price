<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Pricing\Price\CustomerPrice;

use EPuzzle\CustomerPrice\Model\Customer\CustomerProviderInterface;
use EPuzzle\CustomerPrice\Model\CustomerPrice\PriceResolver;
use Magento\Catalog\Model\Product;
use Magento\Framework\Pricing\SaleableInterface;

/**
 * The default customer price collector
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class PriceCollector implements PriceCollectorInterface
{
    /**
     * PriceCollector
     *
     * @param CustomerProviderInterface $customerProvider
     * @param PriceResolver $customerPriceResolver
     */
    public function __construct(
        private readonly CustomerProviderInterface $customerProvider,
        private readonly PriceResolver $customerPriceResolver
    ) {
    }

    /**
     * @inheritDoc
     */
    public function collect(SaleableInterface $product, float $quantity = 1.0): ?float
    {
        /** @var Product $product */
        if ($product->hasData(self::PRICE_CODE)) {
            return (float)$product->getData(self::PRICE_CODE);
        }
        $customerId = $this->customerProvider->getCustomerId();
        if (!$customerId) {
            $product->setData(self::PRICE_CODE);

            return null;
        }
        $price = $this->customerPriceResolver->resolve(
            $customerId,
            $this->customerProvider->getWebsiteId(),
            (int)$product->getId(),
            $quantity
        );
        $product->setData(self::PRICE_CODE, $price);
        if ($price) {
            $product->setData(Product::PRICE, $price);
        }

        return $product->getData(self::PRICE_CODE) ? (float)$product->getData(self::PRICE_CODE) : null;
    }

    /**
     * @inheritDoc
     */
    public function isSearchable(): bool
    {
        return true;
    }
}
