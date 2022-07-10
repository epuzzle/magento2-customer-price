<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Test\Unit\Model\CustomerPrice;

use EPuzzle\CustomerPrice\Model\CustomerPrice;
use EPuzzle\CustomerPrice\Model\ResourceModel\CustomerPrice as CustomerPriceResource;
use Exception;
use Magento\Framework\Exception\CouldNotSaveException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @see CustomerPrice\Save
 */
class SaveTest extends TestCase
{
    /**
     * @var CustomerPrice|MockObject
     */
    private CustomerPrice $entity;

    /**
     * @var CustomerPriceResource|MockObject
     */
    private CustomerPriceResource $resource;

    /**
     * @var CustomerPrice\Save
     */
    private CustomerPrice\Save $save;

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
        $this->save = new CustomerPrice\Save($this->resource);
    }

    /**
     * @throws CouldNotSaveException
     *
     * @see CustomerPrice\Save::execute()
     */
    public function testExecute(): void
    {
        $this->resource->expects($this->once())
            ->method('save')
            ->with($this->entity)
            ->willReturnSelf();
        $this->assertEquals($this->entity->getItemId(), $this->save->execute($this->entity));
    }

    /**
     * @throws CouldNotSaveException
     *
     * @see CustomerPrice\Save::execute()
     */
    public function testExecuteWithException(): void
    {
        $this->resource->expects($this->once())
            ->method('save')
            ->willThrowException(new Exception('error'));
        $this->expectException(CouldNotSaveException::class);
        $this->save->execute($this->entity);
    }
}
