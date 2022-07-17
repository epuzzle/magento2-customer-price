<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Model\Customer;

use EPuzzle\CustomerPrice\Model\Config\Source\Website;
use Exception;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\SessionFactory;

/**
 * Getting information about the customer from the session on the frontend area and other areas
 */
class CustomerProvider implements CustomerProviderInterface
{
    /**
     * @var CustomerRepositoryInterface
     */
    private CustomerRepositoryInterface $customerRepository;

    /**
     * @var SessionFactory
     */
    private SessionFactory $sessionFactory;

    /**
     * CustomerProvider
     *
     * @param CustomerRepositoryInterface $customerRepository
     * @param SessionFactory $sessionFactory
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        SessionFactory $sessionFactory
    ) {
        $this->customerRepository = $customerRepository;
        $this->sessionFactory = $sessionFactory;
    }

    /**
     * @inheritDoc
     */
    public function getCustomerId(): ?int
    {
        $customerId = $this->sessionFactory->create()->getCustomerId();
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
        $customer = $this->getCustomer();
        if ($customer) {
            return (int)$customer->getWebsiteId();
        }

        return Website::DEFAULT_WEBSITE_ID;
    }
}
