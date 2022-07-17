<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Test\Unit\Model\Config\Source;

use EPuzzle\CustomerPrice\Model\Config\Source\Website;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Store\Model\ResourceModel\Website\Collection;
use Magento\Store\Model\ResourceModel\Website\CollectionFactory;
use Magento\Store\Model\Website as StoreWebsite;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @see Website
 */
class WebsiteTest extends TestCase
{
    /**
     * @var Collection|MockObject
     */
    private Collection $collection;

    /**
     * @var Website
     */
    private Website $website;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->collection = $this->createMock(Collection::class);
        $collectionFactory = $this->createMock(CollectionFactory::class);
        $collectionFactory->expects($this->any())
            ->method('create')
            ->willReturn($this->collection);
        $this->website = new Website($collectionFactory);
    }

    /**
     * @see Website::toOptionArray()
     */
    public function testToOptionArray(): void
    {
        $expectedValue = [
            ['value' => 1, 'label' => 'Website 1'],
            ['value' => 2, 'label' => 'Website 2'],
            ['value' => 3, 'label' => 'Website 3'],
        ];

        $options = [];
        foreach ($expectedValue as $valueLable) {
            $options[$valueLable['value']] = $valueLable['label'];
        }

        $this->collection->expects($this->once())
            ->method('getItems')
            ->willReturn($this->getWebsitesByOptinos($options));

        $this->assertEquals($expectedValue, $this->website->toOptionArray());
    }

    /**
     * @see Website::toArray()
     */
    public function testToArray(): void
    {
        $expectedValue = [
            1 => 'Website 1',
            2 => 'Website 2',
            3 => 'Website 3',
        ];

        $this->collection->expects($this->once())
            ->method('getItems')
            ->willReturn($this->getWebsitesByOptinos($expectedValue));

        $this->assertEquals($expectedValue, $this->website->toArray());
    }

    /**
     * @param array $options
     * @return StoreWebsite[]
     */
    private function getWebsitesByOptinos(array $options): array
    {
        $objectManager = new ObjectManager($this);

        $items = [];
        foreach ($options as $value => $label) {
            $items[] = $objectManager->getObject(StoreWebsite::class)
                ->addData(['id' => $value, 'name' => $label]);
        }

        return $items;
    }
}
