<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Plugin\Model\Indexer\Mview\Action;

use Magento\Framework\App\Cache\TypeListInterface;
use Magento\PageCache\Model\Cache\Type;

/**
 * Flushing cache after re-index completed
 */
class FlushCacheAfterReindex
{
    /**
     * @var TypeListInterface
     */
    private TypeListInterface $cacheTypeList;

    /**
     * FlushCacheAfterReindex
     *
     * @param TypeListInterface $cacheTypeList
     */
    public function __construct(
        TypeListInterface $cacheTypeList
    ) {
        $this->cacheTypeList = $cacheTypeList;
    }

    /**
     * Flushing cache after re-index completed
     *
     * @return void
     */
    public function afterExecute(): void
    {
        $this->cacheTypeList->cleanType(Type::TYPE_IDENTIFIER);
    }
}
