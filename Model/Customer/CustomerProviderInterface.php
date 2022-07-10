<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Model\Customer;

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
