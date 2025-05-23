<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Test\Unit\Plugin\App\Http\Context;

use EPuzzle\CustomerPrice\Model\ConfigProvider;
use EPuzzle\CustomerPrice\Model\Customer\CustomerProviderInterface;
use EPuzzle\CustomerPrice\Model\Customer\ExistingCustomerPriceByStrategy;
use EPuzzle\CustomerPrice\Plugin\App\Http\Context\AddCustomerPriceVariableToContext;
use Magento\Framework\App\Http\Context;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @see AddCustomerPriceVariableToContext
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class AddCustomerPriceVariableToContextTest extends TestCase
{
    /**
     * @var Context
     */
    private Context $context;
    /**
     * @var CustomerProviderInterface|MockObject
     */
    private CustomerProviderInterface $customerProvider;
    /**
     * @var ExistingCustomerPriceByStrategy|MockObject
     */
    private ExistingCustomerPriceByStrategy $existingCustomerPriceByStrategy;
    /**
     * @var ConfigProvider|MockObject
     */
    private ConfigProvider $configProvider;
    /**
     * @var AddCustomerPriceVariableToContext
     */
    private AddCustomerPriceVariableToContext $addCustomerPriceVariableToContext;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);
        $this->context = $objectManager->getObject(Context::class);
        $this->customerProvider = $this->createMock(CustomerProviderInterface::class);
        $this->existingCustomerPriceByStrategy = $this->createMock(
            ExistingCustomerPriceByStrategy::class
        );
        $this->configProvider = $this->createMock(ConfigProvider::class);
        $this->addCustomerPriceVariableToContext = new AddCustomerPriceVariableToContext(
            $this->customerProvider,
            $this->existingCustomerPriceByStrategy,
            $this->configProvider
        );
    }

    /**
     * @dataProvider dataProvider
     * @param int $customerId
     * @param int $expectedValue
     * @see AddCustomerPriceVariableToContext::beforeGetVaryString()
     */
    public function testBeforeGetVaryString(int $customerId, int $expectedValue): void
    {
        $this->configProvider->expects($this->once())
            ->method('isConfigured')
            ->willReturn(true);
        $this->customerProvider->expects($this->once())
            ->method('getCustomerId')
            ->willReturn($customerId);
        $this->existingCustomerPriceByStrategy->expects($this->once())
            ->method('execute')
            ->with($customerId, ExistingCustomerPriceByStrategy::STRATEGY_CUSTOMER)
            ->willReturn(true);
        $this->addCustomerPriceVariableToContext->beforeGetVaryString($this->context);
        $this->assertEquals($expectedValue, $this->context->getValue('EP_CUSTOMER_ID'));
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            // [customerId, expectedValue]
            [1, 1],
            [2, 2]
        ];
    }
}
