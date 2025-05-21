<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for customer price search results
 */
interface CustomerPriceSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get customer price list
     *
     * @return \EPuzzle\CustomerPrice\Api\Data\CustomerPriceInterface[]
     */
    public function getItems();

    /**
     * Set customer price list
     *
     * @param \EPuzzle\CustomerPrice\Api\Data\CustomerPriceInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
