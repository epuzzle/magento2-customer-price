<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Plugin\Model\ResourceModel\Product\LinkedProductSelectBuilder;

use EPuzzle\CustomerPrice\Model\ConfigProvider;
use EPuzzle\CustomerPrice\Model\ResourceModel\Product\CustomerPriceBaseSelectProcessor;
use Magento\Catalog\Model\ResourceModel\Product\LinkedProductSelectBuilderInterface;
use Magento\Framework\DB\Select;

/**
 * Used to add the customer prices to the select query
 * We cannot use the select processor through the di.xml because the wee modules rewrite it using the plugin:
 *
 * @see \Magento\Weee\Plugin\Catalog\ResourceModel\Product\WeeeAttributeProductSort::afterBuild()
 */
class LinkedProductCustomerPrice
{
    /**
     * LinkedProductCustomerPriceToSelect
     *
     * @param CustomerPriceBaseSelectProcessor $customerPriceBaseSelectProcessor
     * @param ConfigProvider $configProvider
     */
    public function __construct(
        private readonly CustomerPriceBaseSelectProcessor $customerPriceBaseSelectProcessor,
        private readonly ConfigProvider $configProvider
    ) {
    }

    /**
     * Adds the customer prices to the select query
     *
     * @param LinkedProductSelectBuilderInterface $selectBuilder
     * @param Select[] $selects
     * @return Select[]
     */
    public function afterBuild(
        LinkedProductSelectBuilderInterface $selectBuilder,
        array $selects
    ): array {
        if ($this->configProvider->isConfigured()) {
            foreach ($selects as $select) {
                $this->customerPriceBaseSelectProcessor->process($select);
            }
        }

        return $selects;
    }
}
