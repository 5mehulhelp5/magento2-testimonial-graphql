<?php
/**
 * Copyright © Ashokdubariya. All rights reserved.
 */

declare(strict_types=1);

namespace Ashokdubariya\Testimonial\ViewModel;

use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * ViewModel for Hyva testimonial form
 */
class TestimonialForm implements ArgumentInterface
{
    /**
     * @param UrlInterface $urlBuilder
     * @param FormKey $formKey
     * @param Json $jsonSerializer
     */
    public function __construct(
        private readonly UrlInterface $urlBuilder,
        private readonly FormKey $formKey,
        private readonly Json $jsonSerializer
    ) {
    }

    /**
     * Get form action URL
     *
     * @return string
     */
    public function getFormAction(): string
    {
        return $this->urlBuilder->getUrl('testimonial/submit/post');
    }

    /**
     * Get form key
     *
     * @return string
     */
    public function getFormKey(): string
    {
        return $this->formKey->getFormKey();
    }

    /**
     * Get rating options
     *
     * @return array
     */
    public function getRatingOptions(): array
    {
        return [
            ['value' => 1, 'label' => '1 Star'],
            ['value' => 2, 'label' => '2 Stars'],
            ['value' => 3, 'label' => '3 Stars'],
            ['value' => 4, 'label' => '4 Stars'],
            ['value' => 5, 'label' => '5 Stars'],
        ];
    }

    /**
     * Get rating options as JSON
     *
     * @return string
     */
    public function getRatingOptionsJson(): string
    {
        return $this->jsonSerializer->serialize($this->getRatingOptions());
    }

    /**
     * Get validation rules for Alpine.js
     *
     * @return string
     */
    public function getValidationRules(): string
    {
        $rules = [
            'customer_name' => [
                'required' => true,
                'minLength' => 2,
                'maxLength' => 255,
            ],
            'customer_email' => [
                'required' => true,
                'email' => true,
                'maxLength' => 255,
            ],
            'rating' => [
                'required' => true,
                'min' => 1,
                'max' => 5,
            ],
            'message' => [
                'required' => true,
                'minLength' => 10,
            ],
        ];

        return $this->jsonSerializer->serialize($rules);
    }

    /**
     * Get back URL
     *
     * @return string
     */
    public function getBackUrl(): string
    {
        return $this->urlBuilder->getUrl('testimonial');
    }
}
