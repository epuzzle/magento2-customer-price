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
use Magento\Framework\Exception\CouldNotSaveException;
use PHPUnit\Framework\TestCase;

/**
 * @see CustomerPrice\Save
 */
class SaveTest extends TestCase
{
    private CustomerPrice $entity;
    private CustomerPriceResource $resource;
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
