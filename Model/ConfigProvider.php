<?php

/**
 * Customer prices for Magento 2 platform
 *
 * @author Dmytro Kaplin <dkaplin1994@gmail.com>
 * @license https://github.com/epuzzle/magento2-customer-price/blob/main/LICENSE
 */

declare(strict_types=1);

namespace ePuzzle\CustomerPrice\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Getting config values from the store configuration
 */
class ConfigProvider
{
    private ScopeConfigInterface $scopeConfig;

    /**
     * ConfigProvider
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Is enabled the customer price functionality?
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag('ep_customer_price/general/enabled');
    }
}
