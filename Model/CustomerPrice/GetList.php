<?php

/**
 * Customer prices for Magento 2 platform
 *
 * @author Dmytro Kaplin <dkaplin1994@gmail.com>
 * @license https://github.com/epuzzle/magento2-customer-price/blob/main/LICENSE
 */

declare(strict_types=1);

namespace ePuzzle\CustomerPrice\Model\CustomerPrice;

use ePuzzle\CustomerPrice\Api\Data\CustomerPriceInterface;
use ePuzzle\CustomerPrice\Model\ResourceModel\CustomerPrice;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Get a list of customer prices
 */
class GetList
{
    private CustomerPrice\CollectionFactory $collectionFactory;
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
