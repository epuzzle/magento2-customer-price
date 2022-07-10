<?php

/**
 * Customer prices for Magento 2 platform
 *
 * @author Dmytro Kaplin <dkaplin1994@gmail.com>
 * @license https://github.com/epuzzle/magento2-customer-price/blob/main/LICENSE
 */

declare(strict_types=1);

namespace ePuzzle\CustomerPrice\Test\Unit\Model\CustomerPrice;

use ePuzzle\CustomerPrice\Model\CustomerPrice;
use ePuzzle\CustomerPrice\Model\ResourceModel\CustomerPrice as CustomerPriceResource;
use Exception;
use Magento\Framework\Exception\CouldNotDeleteException;
use PHPUnit\Framework\TestCase;

/**
 * @see CustomerPrice\Delete
 */
class DeleteTest extends TestCase
{
    private CustomerPrice $entity;
    private CustomerPriceResource $resource;
    private CustomerPrice\Delete $delete;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->entity = $this->getMockBuilder(CustomerPrice::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->entity->expects($this->any())
            ->method('getItemId')
            ->willReturn(1);
        $this->resource = $this->getMockBuilder(CustomerPriceResource::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->delete = new CustomerPrice\Delete($this->resource);
    }

    /**
     * @throws CouldNotDeleteException
     *
     * @see CustomerPrice\Delete::execute()
     */
    public function testExecute(): void
    {
        $this->resource->expects($this->once())
            ->method('delete')
            ->with($this->entity)
            ->willReturnSelf();
        $this->delete->execute($this->entity);
    }

    /**
     * @throws CouldNotDeleteException
     *
     * @see CustomerPrice\Delete::execute()
     */
    public function testExecuteWithException(): void
    {
        $this->resource->expects($this->once())
            ->method('delete')
            ->willThrowException(new Exception('error'));
        $this->expectException(CouldNotDeleteException::class);
        $this->delete->execute($this->entity);
    }
}
