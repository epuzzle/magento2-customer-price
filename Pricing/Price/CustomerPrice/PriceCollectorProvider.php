<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Pricing\Price\CustomerPrice;

use EPuzzle\CustomerPrice\Model\ConfigProvider;
use Magento\Framework\Exception\InvalidArgumentException;
use Magento\Framework\Exception\StateException;

/**
 * Used to provide the customer price collector
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class PriceCollectorProvider
{
    /**
     * PriceCollectorProvider
     *
     * @param ConfigProvider $configProvider
     */
    public function __construct(
        private readonly ConfigProvider $configProvider
    ) {
    }

    /**
     * Provides the customer price collector
     *
     * @return PriceCollectorInterface
     * @throws InvalidArgumentException
     * @throws StateException
     */
    public function get(): PriceCollectorInterface
    {
        if (!$this->configProvider->isEnabled()) {
            throw new StateException(__('The module is disabled.'));
        }
        $collector = $this->configProvider->getCollectorByType();
        if (!$collector) {
            $type = $this->configProvider->getCollectorType();
            throw new InvalidArgumentException(
                __('Invalid price collector type: %type', ['type' => $type])
            );
        }

        return $collector;
    }

    /**
     * Get the customer price collector if an error occurred, then return null
     *
     * @return PriceCollectorInterface|null
     */
    public function getWithNull(): ?PriceCollectorInterface
    {
        try {
            return $this->get();
        } catch (InvalidArgumentException|StateException) {
            return null;
        }
    }
}
