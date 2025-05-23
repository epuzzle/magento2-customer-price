<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Model\ResourceModel\Product;

use EPuzzle\CustomerPrice\Model\Customer\CustomerProviderInterface;
use EPuzzle\CustomerPrice\Model\ResourceModel\CustomerPrice;
use Magento\Catalog\Model\ResourceModel\Product\BaseSelectProcessorInterface;
use Magento\Framework\DB\Select;
use Psr\Log\LoggerInterface;
use Throwable;
use Zend_Db_Expr;

/**
 * Used to add the customer prices to the select query
 */
class CustomerPriceBaseSelectProcessor implements BaseSelectProcessorInterface
{
    public const CUSTOMER_PRICE_TABLE_ALIAS = 'ecp';
    /**
     * @var array
     */
    private array $updatedSelects = [];

    /**
     * CustomerPriceBaseSelectProcessor
     *
     * @param CustomerProviderInterface $customerProvider
     * @param CustomerPrice $customerPrice
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly CustomerProviderInterface $customerProvider,
        private readonly CustomerPrice $customerPrice,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * @inheritDoc
     */
    public function process(Select $select)
    {
        if (!$customerId = $this->customerProvider->getCustomerId()) {
            // exit: the customer isn't logged in
            return $select;
        }
        $objectId = spl_object_id($select);
        if (isset($this->updatedSelects[$objectId])) {
            // exit: the select was already updated
            return $select;
        }
        try {
            $productAlias = BaseSelectProcessorInterface::PRODUCT_TABLE_ALIAS;
            $customerPriceAlias = self::CUSTOMER_PRICE_TABLE_ALIAS;
            $select->joinLeft(
                [self::CUSTOMER_PRICE_TABLE_ALIAS => $this->customerPrice->getMainTable()],
                "{$customerPriceAlias}.product_id = {$productAlias}.entity_id"
                . " AND {$customerPriceAlias}.customer_id = {$customerId}",
                null
            );
            $minPriceField = str_contains(json_encode($select->getPart(Select::ORDER)), 'weee_min_price')
                ? 'LEAST((t.min_price + IFNULL(weee_child.value, IFNULL(weee_parent.value, 0))), ecp.price)'
                : 'LEAST(t.min_price, ecp.price)';
            $select->columns(['epuzzle_min_price' => new Zend_Db_Expr($minPriceField)]);
            $select->reset(Select::ORDER);
            $select->order('epuzzle_min_price ' . Select::SQL_DESC);
            $this->updatedSelects[$objectId] = true;
        } catch (Throwable $exception) {
            $this->logger->critical($exception);
        }

        return $select;
    }
}
