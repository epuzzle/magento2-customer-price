<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Test\Unit\Model\Product;

use EPuzzle\CustomerPrice\Model\Product\GetParentIds;
use Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @see GetParentIds
 */
class GetParentIdsTest extends TestCase
{
    /**
     * @var Configurable|MockObject
     */
    private Configurable $configurable;

    /**
     * @var GetParentIds
     */
    private GetParentIds $getParentIds;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->configurable = $this->createMock(Configurable::class);
        $this->getParentIds = new GetParentIds($this->configurable);
    }

    /**
     * @see GetParentIds::execute()
     */
    public function testExecute(): void
    {
        $expectedValue = [2, 3, 4];
        $productId = 2;
        $this->configurable->expects($this->once())
            ->method('getParentIdsByChild')
            ->with($productId)
            ->willReturn($expectedValue);

        $this->assertEquals($expectedValue, $this->getParentIds->execute($productId));
    }
}
