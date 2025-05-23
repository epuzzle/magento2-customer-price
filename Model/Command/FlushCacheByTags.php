<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Model\Command;

use Magento\Framework\App\Cache\StateInterface;
use Magento\Framework\App\Cache\Tag\Resolver;
use Magento\Framework\App\Cache\Type\FrontendPool;
use Magento\Framework\Model\AbstractModel;
use Zend_Cache;

/**
 * Flush cache by tags for entities
 */
class FlushCacheByTags
{
    /**
     * FlushCacheByTags
     *
     * @param FrontendPool $cachePool
     * @param StateInterface $cacheState
     * @param string[] $cacheList
     * @param Resolver $tagResolver
     */
    public function __construct(
        private readonly FrontendPool $cachePool,
        private readonly StateInterface $cacheState,
        private array $cacheList,
        private readonly Resolver $tagResolver
    ) {
    }

    /**
     * Flush cache by tags for the entity
     *
     * @param AbstractModel $object
     * @return void
     */
    public function execute(AbstractModel $object): void
    {
        if ($tags = $this->tagResolver->getTags($object)) {
            $uniqueTags = null;
            foreach ($this->cacheList as $cacheType) {
                if ($this->cacheState->isEnabled($cacheType)) {
                    $this->cachePool->get($cacheType)->clean(
                        Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG,
                        $uniqueTags = $uniqueTags ?? array_unique($tags)
                    );
                }
            }
        }
    }
}
