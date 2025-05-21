<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Model\CustomerPrice;

use EPuzzle\CustomerPrice\Api\Data\CustomerPriceSearchResultsInterface;
use EPuzzle\CustomerPrice\Model\CustomerPrice;
use EPuzzle\CustomerPrice\Model\ResourceModel\CustomerPrice\CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Get a list of customer prices
 */
class GetList
{
    /**
     * GetList
     *
     * @param CollectionFactory $collectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param CustomerPriceSearchResultsInterface $searchResults
     */
    public function __construct(
        private readonly CollectionFactory $collectionFactory,
        private readonly CollectionProcessorInterface $collectionProcessor,
        private readonly CustomerPriceSearchResultsInterface $searchResults
    ) {
    }

    /**
     * Get a list of customer prices
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return CustomerPriceSearchResultsInterface
     */
    public function execute(SearchCriteriaInterface $searchCriteria): CustomerPriceSearchResultsInterface
    {
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        /** @var CustomerPrice[] $items */
        $items = $collection->getItems();
        $this->searchResults->setItems($items);
        $this->searchResults->setTotalCount($collection->getSize());
        $this->searchResults->setSearchCriteria($searchCriteria);

        return $this->searchResults;
    }
}
