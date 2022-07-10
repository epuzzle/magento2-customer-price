<?php

/**
 * Customer prices for Magento 2 platform
 *
 * @author Dmytro Kaplin <dkaplin1994@gmail.com>
 * @license https://github.com/epuzzle/magento2-customer-price/blob/main/LICENSE
 */

declare(strict_types=1);

namespace ePuzzle\CustomerPrice\Model\Customer;

use Magento\Customer\Api\Data\CustomerInterface;

/**
 * Getting information about the customer
 */
interface CustomerProviderInterface
{
    /**
     * Get the customer ID
     *
     * @return int|null
     */
    public function getCustomerId(): ?int;

    /**
     * Get the customer
     *
     * @return \Magento\Customer\Api\Data\CustomerInterface|null
     */
    public function getCustomer(): ?CustomerInterface;

    /**
     * Get the customer website ID
     *
     * @return int
     */
    public function getWebsiteId(): int;
}
