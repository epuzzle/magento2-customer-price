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
     * @var Configurable
     */
    private Configurable $configurable;

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
        Configurable $configurable
    ) {
        $this->configurable = $configurable;
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

        return $this->cache[$productId] = $this->configurable->getParentIdsByChild($productId);
    }
}
