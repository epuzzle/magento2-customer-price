<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Plugin\Model\ResourceModel\CustomerPrice\CustomerPrice;

use EPuzzle\CustomerPrice\Model\Command\FlushCacheByTags;
use EPuzzle\CustomerPrice\Model\CustomerPrice;
use EPuzzle\CustomerPrice\Model\Product\GetParentIds;
use EPuzzle\CustomerPrice\Model\ResourceModel\CustomerPrice as CustomerPriceResource;
use Magento\Catalog\Model\ProductFactory;
use Magento\CatalogSearch\Model\Indexer\Fulltext;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\Indexer\IndexerRegistry;
use Magento\Framework\Model\AbstractModel;
use Magento\PageCache\Model\Cache\Type;

/**
 * Re-index and flush cache for catalog
 */
class ReindexAndFlushCache
{
    /**
     * ReindexAndFlushCache
     *
     * @param IndexerRegistry $indexerRegistry
     * @param TypeListInterface $cacheTypeList
     * @param FlushCacheByTags $flushCacheByTags
     * @param ProductFactory $productFactory
     * @param GetParentIds $getParentIds
     */
    public function __construct(
        private readonly IndexerRegistry $indexerRegistry,
        private readonly TypeListInterface $cacheTypeList,
        private readonly FlushCacheByTags $flushCacheByTags,
        private readonly ProductFactory $productFactory,
        private readonly GetParentIds $getParentIds
    ) {
    }

    /**
     * Re-index the catalog search indexer after saving the entity
     *
     * @param CustomerPriceResource $resource
     * @param CustomerPrice $entity
     * @return void
     * @see CustomerPrice::save()
     */
    public function beforeSave(
        CustomerPriceResource $resource,
        CustomerPrice $entity
    ): void {
        $resource->addCommitCallback(function () use ($entity) {
            $this->reindexRow((int)$entity->getProductId());
        });
    }

    /**
     * Clean up the full page cache after saving
     *
     * @param CustomerPriceResource $customerPrice
     * @param CustomerPriceResource $result
     * @param CustomerPrice $entity
     * @return CustomerPriceResource
     * @see CustomerPrice::save()
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterSave(
        CustomerPriceResource $customerPrice,
        CustomerPriceResource $result,
        CustomerPrice $entity
    ): CustomerPriceResource {
        $this->flushCacheForEntity(
            $this->productFactory->create()
                ->setId($entity->getProductId())
        );
        foreach ($this->getParentIds->execute((int)$entity->getProductId()) as $productId) {
            $this->flushCacheForEntity(
                $this->productFactory->create()
                    ->setId($productId)
            );
        }

        return $result;
    }

    /**
     * Re-index the catalog search indexer after deleting the entity
     *
     * @param CustomerPriceResource $customerPrice
     * @param CustomerPrice $entity
     * @return void
     * @see CustomerPrice::delete()
     */
    public function beforeDelete(
        CustomerPriceResource $customerPrice,
        CustomerPrice $entity
    ): void {
        $customerPrice->addCommitCallback(function () use ($entity) {
            $this->reindexRow((int)$entity->getProductId());
        });
    }

    /**
     * Clean up the full page cache after deleting
     *
     * @param CustomerPriceResource $customerPrice
     * @param CustomerPriceResource $result
     * @param CustomerPrice $entity
     * @return CustomerPriceResource
     * @see CustomerPrice::delete()
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterDelete(
        CustomerPriceResource $customerPrice,
        CustomerPriceResource $result,
        CustomerPrice $entity
    ): CustomerPriceResource {
        $this->flushCacheForEntity(
            $this->productFactory->create()->setId($entity->getProductId())
        );

        return $result;
    }

    /**
     * Re-index product by ID
     *
     * @param int $productId
     * @return void
     */
    private function reindexRow(int $productId): void
    {
        $indexer = $this->indexerRegistry->get(Fulltext::INDEXER_ID);
        if (!$indexer->isScheduled()) {
            $indexer->reindexList([$productId, ...$this->getParentIds->execute($productId)]);
        }
    }

    /**
     * Flush cache for the entity
     *
     * @param AbstractModel $entity
     */
    private function flushCacheForEntity(AbstractModel $entity): void
    {
        // clean up the entity cache by tags
        $this->flushCacheByTags->execute($entity);
        if (!$this->isScheduled(Fulltext::INDEXER_ID)) {
            // clean up full page cache
            $this->cacheTypeList->cleanType(Type::TYPE_IDENTIFIER);
        }
    }

    /**
     * Is scheduled the indexer?
     *
     * @param string $indexer
     * @return bool
     */
    public function isScheduled(string $indexer): bool
    {
        $indexer = $this->indexerRegistry->get($indexer);

        return $indexer->isScheduled();
    }
}
