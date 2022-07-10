<?php

/**
 * Customer prices for Magento 2 platform
 *
 * @author Dmytro Kaplin <dkaplin1994@gmail.com>
 * @license https://github.com/epuzzle/magento2-customer-price/blob/main/LICENSE
 */

declare(strict_types=1);

namespace ePuzzle\CustomerPrice\Plugin\App\Http\Context;

use ePuzzle\CustomerPrice\Model\Customer\CustomerProviderInterface;
use ePuzzle\CustomerPrice\Model\Customer\ExistingCustomerPriceByStrategy;
use Magento\Framework\App\Http\Context;

/**
 * Adding the customer price variable to the context
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class AddCustomerPriceVariableToContext
{
    private const KEY = 'EP_CUSTOMER_ID';

    private CustomerProviderInterface $customerProvider;
    private ExistingCustomerPriceByStrategy $existingCustomerPriceByStrategy;

    /**
     * AddCustomerPriceVariableToContext
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
     * Adding the customer price variable to the context
     *
     * @param Context $context
     * @return void
     */
    public function beforeGetVaryString(Context $context): void
    {
        if (($customerId = $this->customerProvider->getCustomerId())
            && $this->existingCustomerPriceByStrategy->execute(
                (int)$customerId,
                ExistingCustomerPriceByStrategy::STRATEGY_CUSTOMER
            )
        ) {
            $context->setValue(self::KEY, $customerId, false);
        }
    }
}
