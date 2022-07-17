<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Test\Unit\Model\Customer;

use EPuzzle\CustomerPrice\Model\Customer\CustomerProvider;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Data\Customer;
use Magento\Customer\Model\Session;
use Magento\Customer\Model\SessionFactory;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @see CustomerProvider
 */
class CustomerProviderTest extends TestCase
{
    /**
     * @var Session|MockObject
     */
    private Session $session;

    /**
     * @var CustomerRepositoryInterface|MockObject
     */
    private CustomerRepositoryInterface $customerRepository;

    /**
     * @var CustomerProvider
     */
    private CustomerProvider $customerProvider;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->session = $this->createMock(Session::class);
        $sessionFactory = $this->createMock(SessionFactory::class);
        $sessionFactory->expects($this->any())
            ->method('create')
            ->willReturn($this->session);
        $this->customerRepository = $this->createMock(CustomerRepositoryInterface::class);

        $this->customerProvider = new CustomerProvider(
            $this->customerRepository,
            $sessionFactory
        );
    }

    /**
     * @see CustomerProvider::getCustomerId()
     */
    public function testGetCustomerId(): void
    {
        $expectedValue = 1;

        $this->session->expects($this->once())
            ->method('getCustomerId')
            ->willReturn($expectedValue);

        $this->assertEquals($expectedValue, $this->customerProvider->getCustomerId());
    }

    /**
     * @see CustomerProvider::getCustomer()
     */
    public function testGetCustomer(): void
    {
        $objectManager = new ObjectManager($this);

        $customerId = 1;
        $expectedValue = $objectManager->getObject(Customer::class);

        $this->session->expects($this->once())
            ->method('getCustomerId')
            ->willReturn($customerId);

        $this->customerRepository->expects($this->once())
            ->method('getById')
            ->with($customerId)
            ->willReturn($expectedValue);

        $this->assertEquals($expectedValue, $this->customerProvider->getCustomer());
    }

    /**
     * @see CustomerProvider::getWebsiteId()
     */
    public function testGetWebsiteId(): void
    {
        $objectManager = new ObjectManager($this);
        $expectedValue = 1;
        $customerId = 1;
        $customer = $objectManager->getObject(Customer::class)
            ->setWebsiteId($expectedValue);

        $this->session->expects($this->once())
            ->method('getCustomerId')
            ->willReturn($customerId);

        $this->customerRepository->expects($this->once())
            ->method('getById')
            ->with($customerId)
            ->willReturn($customer);

        $this->assertEquals($expectedValue, $this->customerProvider->getWebsiteId());
    }
}
