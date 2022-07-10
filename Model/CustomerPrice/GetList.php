<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Model\CustomerPrice;

use EPuzzle\CustomerPrice\Api\Data\CustomerPriceInterface;
use EPuzzle\CustomerPrice\Model\ResourceModel\CustomerPrice;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Get a list of customer prices
 */
class GetList
{
    /**
     * @var CustomerPrice\CollectionFactory
     */
    private CustomerPrice\CollectionFactory $collectionFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private CollectionProcessorInterface $collectionProcessor;

    /**
     * GetList
     *
     * @param CustomerPrice\CollectionFactory $collectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        CustomerPrice\CollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * Get a list of customer prices
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return CustomerPriceInterface[]
     */
    public function execute(SearchCriteriaInterface $searchCriteria): array
    {
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        return $collection->getItems();
    }
}
