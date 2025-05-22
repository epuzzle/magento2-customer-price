<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Pricing\Price\CustomerPrice;

use EPuzzle\CustomerPrice\Model\ConfigProvider;
use Magento\Framework\Exception\InvalidArgumentException;

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
     * @param PriceCollectorInterface[] $collectors
     */
    public function __construct(
        private readonly ConfigProvider $configProvider,
        private array $collectors = []
    ) {
    }

    /**
     * Provides the customer price collector
     *
     * @param string|null $type
     * @return PriceCollectorInterface
     * @throws InvalidArgumentException
     */
    public function get(?string $type = null): PriceCollectorInterface
    {
        $type = $type ?: $this->configProvider->getCollectorType();
        if (!isset($this->collectors[$type])) {
            throw new InvalidArgumentException(
                __('Invalid price collector type: %type', ['type' => $type])
            );
        }

        return $this->collectors[$type];
    }

    /**
     * Get the customer price collector if an error occurred, then return null
     *
     * @param string|null $type
     * @return PriceCollectorInterface|null
     */
    public function getWithNull(?string $type = null): ?PriceCollectorInterface
    {
        try {
            return $this->get($type);
        } catch (InvalidArgumentException) {
            return null;
        }
    }
}
