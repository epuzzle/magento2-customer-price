<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Model\Product;

use Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable;

/**
 * Get the list of parents product IDs
 */
class GetParentIds
{
    /**
     * @var array
     */
    private array $cache = [];

    /**
     * GetParentIds
     *
     * @param Configurable $configurable
     */
    public function __construct(
        private readonly Configurable $configurable
    ) {
    }

    /**
     * Get the list of parents product IDs
     *
     * @param int $productId
     * @return int[]
     */
    public function execute(int $productId): array
    {
        if (isset($this->cache[$productId])) {
            return $this->cache[$productId];
        }

        return $this->cache[$productId] = array_map(
            static fn ($id) => (int)$id,
            $this->configurable->getParentIdsByChild($productId)
        );
    }
}
