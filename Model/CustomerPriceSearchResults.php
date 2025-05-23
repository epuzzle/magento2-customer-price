<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Model;

use EPuzzle\CustomerPrice\Api\Data\CustomerPriceInterface;
use EPuzzle\CustomerPrice\Api\Data\CustomerPriceSearchResultsInterface;
use Magento\Framework\Api\SearchResults;

/**
 * Service data object with customer price search results
 */
class CustomerPriceSearchResults extends SearchResults implements CustomerPriceSearchResultsInterface
{
    /**
     * @inheritDoc
     */
    // phpcs:ignore Generic.CodeAnalysis.UselessOverridingMethod.Found
    public function getItems()
    {
        return parent::getItems();
    }

    /**
     * @inheritDoc
     */
    // phpcs:ignore Generic.CodeAnalysis.UselessOverridingMethod.Found
    public function setItems(array $items)
    {
        return parent::setItems($items);
    }
}
