<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Observer;

use EPuzzle\CustomerPrice\Pricing\Price\CustomerPriceFactory;
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
    /**
     * ProcessFinalPriceObserver
     *
     * @param CustomerPriceFactory $customerPriceFactory
     */
    public function __construct(
        private readonly CustomerPriceFactory $customerPriceFactory
    ) {
    }

    /**
     * Apply customer price to product on frontend
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        /** @var Product $product */
        $product = $observer->getEvent()->getProduct();
        $priceModel = $product->getData('epuzzle_customer_price_model');
        if (!$priceModel) {
            $priceModel = $this->customerPriceFactory->create([
                'saleableItem' => $product,
                'quantity' => (float)$observer->getEvent()->getQty()
            ]);
            $product->setData('epuzzle_customer_price_model', $priceModel);
        }
        $price = $priceModel->getValue();
        if ($price) {
            $product->setFinalPrice($price);
        }
    }
}
