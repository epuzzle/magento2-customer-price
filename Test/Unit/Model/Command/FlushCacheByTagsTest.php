<?php

/**
 * Customer prices for Magento 2 platform
 *
 * @author Dmytro Kaplin <dkaplin1994@gmail.com>
 * @license https://github.com/epuzzle/magento2-customer-price/blob/main/LICENSE
 */

declare(strict_types=1);

namespace ePuzzle\CustomerPrice\Test\Unit\Model\Command;

use ePuzzle\CustomerPrice\Model\Command\FlushCacheByTags;
use Magento\Framework\App\Cache\StateInterface;
use Magento\Framework\App\Cache\Tag\Resolver;
use Magento\Framework\App\Cache\Type\Block;
use Magento\Framework\App\Cache\Type\Collection;
use Magento\Framework\App\Cache\Type\FrontendPool;
use Magento\Framework\Cache\FrontendInterface;
use Magento\Framework\Model\AbstractModel;
use PHPUnit\Framework\TestCase;

/**
 * @see FlushCacheByTags
 */
class FlushCacheByTagsTest extends TestCase
{
    private FrontendPool $frontendPool;
    private StateInterface $state;
    private Resolver $resolver;
    private FlushCacheByTags $flushCacheByTags;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->frontendPool = $this->createMock(FrontendPool::class);
        $this->state = $this->createMock(StateInterface::class);
        $this->resolver = $this->createMock(Resolver::class);
        $this->flushCacheByTags = new FlushCacheByTags(
            $this->frontendPool,
            $this->state,
            [
                'block_html' => Block::TYPE_IDENTIFIER,
                'collections' => Collection::TYPE_IDENTIFIER,
            ],
            $this->resolver
        );
    }

    /**
     * @see FlushCacheByTags::execute()
     */
    public function testExecute(): void
    {
        $entity = $this->createMock(AbstractModel::class);
        $frontend = $this->createMock(FrontendInterface::class);
        $this->resolver->expects($this->once())
            ->method('getTags')
            ->with($entity)
            ->willReturn(['cat_p_1']);
        $this->state->expects($this->any())
            ->method('isEnabled')
            ->willReturn(true);
        $this->frontendPool->expects($this->any())
            ->method('get')
            ->willReturn($frontend);
        $frontend->expects($this->any())
            ->method('clean')
            ->willReturn(true);
        $this->flushCacheByTags->execute($entity);
    }
}
