<?php
/**
 * Copyright © Ashokdubariya. All rights reserved.
 */

declare(strict_types=1);

namespace Ashokdubariya\Testimonial\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Status source model
 */
class Status implements OptionSourceInterface
{
    /**
     * Status values
     */
    public const DISABLED = 0;
    public const ENABLED = 1;

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        return [
            ['value' => self::ENABLED, 'label' => __('Enabled')],
            ['value' => self::DISABLED, 'label' => __('Disabled')]
        ];
    }
}
