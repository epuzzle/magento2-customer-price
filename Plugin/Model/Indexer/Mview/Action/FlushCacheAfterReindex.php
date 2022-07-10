<?php

/**
 * Customer prices for Magento 2 platform
 *
 * @author Dmytro Kaplin <dkaplin1994@gmail.com>
 * @license https://github.com/epuzzle/magento2-customer-price/blob/main/LICENSE
 */

declare(strict_types=1);

namespace ePuzzle\CustomerPrice\Plugin\Model\Indexer\Mview\Action;

use Magento\Framework\App\Cache\TypeListInterface;
use Magento\PageCache\Model\Cache\Type;

/**
 * Flushing cache after re-index completed
 */
class FlushCacheAfterReindex
{
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
