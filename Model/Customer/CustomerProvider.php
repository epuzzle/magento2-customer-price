<?php

/**
 * Customer prices for Magento 2 platform
 *
 * @author Dmytro Kaplin <dkaplin1994@gmail.com>
 * @license https://github.com/epuzzle/magento2-customer-price/blob/main/LICENSE
 */

declare(strict_types=1);

namespace ePuzzle\CustomerPrice\Model\Customer;

use ePuzzle\CustomerPrice\Model\Config\Source\Website;
use Exception;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\Session;

/**
 * Getting information about the customer from the session on the frontend area and other areas
 *
 * @SuppressWarnings(PHPMD.CookieAndSessionMisuse)
 */
class CustomerProvider implements CustomerProviderInterface
{
    private CustomerRepositoryInterface $customerRepository;
    private Session $customerSession;

    /**
     * CustomerProvider
     *
     * @param CustomerRepositoryInterface $customerRepository
     * @param Session $customerSession
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        Session $customerSession
    ) {
        $this->customerRepository = $customerRepository;
        $this->customerSession = $customerSession;
    }

    /**
     * @inheritDoc
     */
    public function getCustomerId(): ?int
    {
        $customerId = $this->customerSession->getCustomerId();
        return '' !== (string)$customerId ? (int)$customerId : null;
    }

    /**
     * @inheritDoc
     */
    public function getCustomer(): ?CustomerInterface
    {
        $customerId = $this->getCustomerId();
        if ($customerId) {
            try {
                return $this->customerRepository->getById($customerId);
            } catch (Exception $exception) { // phpcs:ignore Magento2.CodeAnalysis.EmptyBlock.DetectedCatch
                // could not get the customer: the customer does not exist
            }
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function getWebsiteId(): int
    {
        return $this->getCustomer() ? (int)$this->getCustomer()->getWebsiteId() : Website::DEFAULT_WEBSITE_ID;
    }
}
