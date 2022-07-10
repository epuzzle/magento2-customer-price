<?php

/**
 * Customer prices for Magento 2 platform
 *
 * @author Dmytro Kaplin <dkaplin1994@gmail.com>
 * @license https://github.com/epuzzle/magento2-customer-price/blob/main/LICENSE
 */

declare(strict_types=1);

namespace ePuzzle\CustomerPrice\Observer;

use ePuzzle\CustomerPrice\Model\Customer\CustomerProviderInterface;
use ePuzzle\CustomerPrice\Model\CustomerPrice\PriceResolver;
use Magento\Catalog\Model\Product;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Processing the customer price for the product on the frontend area
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class ProcessFinalPriceObserver implements ObserverInterface
{
    private CustomerProviderInterface $customerProvider;
    private PriceResolver $customerPriceResolver;

    /**
     * ProcessFinalPriceObserver
     *
     * @param CustomerProviderInterface $customerProvider
     * @param PriceResolver $customerPriceResolver
     */
    public function __construct(
        CustomerProviderInterface $customerProvider,
        PriceResolver $customerPriceResolver
    ) {
        $this->customerProvider = $customerProvider;
        $this->customerPriceResolver = $customerPriceResolver;
    }

    /**
     * Apply customer price to product on frontend
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        $customerId = $this->customerProvider->getCustomerId();
        if ($customerId) {
            /** @var Product $product */
            $product = $observer->getEvent()->getProduct();
            $price = $this->customerPriceResolver->resolve(
                $customerId,
                $this->customerProvider->getWebsiteId(),
                (int)$product->getId(),
                (float)$observer->getEvent()->getQty()
            );

            if ($price) {
                $product->setFinalPrice($price);
            }
        }
    }
}
