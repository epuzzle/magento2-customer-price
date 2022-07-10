<?php

/**
 * Customer prices for Magento 2 platform
 *
 * @author Dmytro Kaplin <dkaplin1994@gmail.com>
 * @license https://github.com/epuzzle/magento2-customer-price/blob/main/LICENSE
 */

declare(strict_types=1);

namespace ePuzzle\CustomerPrice\Test\Unit\Model\CustomerPrice;

use ePuzzle\CustomerPrice\Model\CustomerPrice\PriceResolver;
use ePuzzle\CustomerPrice\Model\ResourceModel\CustomerPrice as CustomerPriceResource;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Select;
use PHPUnit\Framework\TestCase;

/**
 * @see PriceResolver
 */
class PriceResolverTest extends TestCase
{
    private AdapterInterface $connection;
    private PriceResolver $resolver;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $select = $this->getMockBuilder(Select::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['where', 'order', 'from', 'limit'])
            ->getMock();
        $select->expects($this->any())
            ->method('from')
            ->with(CustomerPriceResource::TABLE_NAME, 'price')
            ->willReturnSelf();
        $select->expects($this->any())
            ->method('where')
            ->willReturnSelf();
        $select->expects($this->any())
            ->method('order')
            ->with('qty DESC')
            ->willReturnSelf();
        $select->expects($this->any())
            ->method('limit')
            ->with(1)
            ->willReturnSelf();
        $this->connection = $this->getMockBuilder(AdapterInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->connection->expects($this->any())
            ->method('select')
            ->willReturn($select);
        $resource = $this->getMockBuilder(CustomerPriceResource::class)
            ->disableOriginalConstructor()
            ->getMock();
        $resource->expects($this->any())
            ->method('getConnection')
            ->willReturn($this->connection);
        $resource->expects($this->any())
            ->method('getMainTable')
            ->willReturn(CustomerPriceResource::TABLE_NAME);
        $this->resolver = new PriceResolver($resource);
    }

    /**
     * @dataProvider dataProvider
     * @param int $customerId
     * @param int $websiteId
     * @param int $productId
     * @param float $qty
     * @param float $expectedValue
     * @see PriceResolver::resolve()
     */
    public function testResolve(
        int $customerId,
        int $websiteId,
        int $productId,
        float $qty,
        float $expectedValue
    ): void {
        $this->connection->expects($this->once())
            ->method('fetchOne')
            ->willReturn($expectedValue);
        $this->assertEquals(
            $expectedValue,
            $this->resolver->resolve(
                $customerId,
                $websiteId,
                $productId,
                $qty
            )
        );
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            // [customerId, websiteId, productId, qty, expectedValue]
            [1, 1, 1, 1, 10.1],
            [1, 2, 1, 2, 11.2],
            [1, 3, 1, 3, 12.3]
        ];
    }
}
