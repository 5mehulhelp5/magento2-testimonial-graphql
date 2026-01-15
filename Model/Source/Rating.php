<?php
/**
 * Copyright © Ashokdubariya. All rights reserved.
 */

declare(strict_types=1);

namespace Ashokdubariya\Testimonial\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Rating source model
 */
class Rating implements OptionSourceInterface
{
    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        return [
            ['value' => 1, 'label' => __('1 Star')],
            ['value' => 2, 'label' => __('2 Stars')],
            ['value' => 3, 'label' => __('3 Stars')],
            ['value' => 4, 'label' => __('4 Stars')],
            ['value' => 5, 'label' => __('5 Stars')]
        ];
    }
}
