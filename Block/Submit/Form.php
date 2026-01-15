<?php
/**
 * Copyright © Ashokdubariya. All rights reserved.
 */

declare(strict_types=1);

namespace Ashokdubariya\Testimonial\Block\Submit;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

/**
 * Form block
 */
class Form extends Template
{
    /**
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Get form action URL
     *
     * @return string
     */
    public function getFormAction(): string
    {
        return $this->getUrl('testimonial/submit/post');
    }

    /**
     * Get rating options
     *
     * @return array
     */
    public function getRatingOptions(): array
    {
        return [
            1 => __('1 Star'),
            2 => __('2 Stars'),
            3 => __('3 Stars'),
            4 => __('4 Stars'),
            5 => __('5 Stars')
        ];
    }
}
