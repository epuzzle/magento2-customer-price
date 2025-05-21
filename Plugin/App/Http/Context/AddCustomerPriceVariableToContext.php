<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Plugin\App\Http\Context;

use EPuzzle\CustomerPrice\Model\Customer\CustomerProviderInterface;
use EPuzzle\CustomerPrice\Model\Customer\ExistingCustomerPriceByStrategy;
use Magento\Framework\App\Http\Context;

/**
 * Adding the customer price variable to the context
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class AddCustomerPriceVariableToContext
{
    private const KEY = 'EP_CUSTOMER_ID';

    /**
     * AddCustomerPriceVariableToContext
     *
     * @param CustomerProviderInterface $customerProvider
     * @param ExistingCustomerPriceByStrategy $existingCustomerPriceByStrategy
     */
    public function __construct(
        private readonly CustomerProviderInterface $customerProvider,
        private readonly ExistingCustomerPriceByStrategy $existingCustomerPriceByStrategy
    ) {
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
