<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Model\Customer\Adminhtml;

use EPuzzle\CustomerPrice\Model\Config\Source\Website;
use EPuzzle\CustomerPrice\Model\Customer\CustomerProviderInterface;
use Exception;
use Magento\Backend\Model\Session\QuoteFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;

/**
 * Getting information about the customer from the session on the adminhtml area
 */
class CustomerProvider implements CustomerProviderInterface
{
    /**
     * @var CustomerRepositoryInterface
     */
    private CustomerRepositoryInterface $customerRepository;

    /**
     * @var QuoteFactory
     */
    private QuoteFactory $sessionFactory;

    /**
     * CustomerProvider
     *
     * @param CustomerRepositoryInterface $customerRepository
     * @param QuoteFactory $sessionFactory
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        QuoteFactory $sessionFactory
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
        return $this->getCustomer() ? (int)$this->getCustomer()->getWebsiteId() : Website::DEFAULT_WEBSITE_ID;
    }
}
