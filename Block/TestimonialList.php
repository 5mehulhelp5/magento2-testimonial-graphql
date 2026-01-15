<?php
/**
 * Copyright © Ashokdubariya. All rights reserved.
 */

declare(strict_types=1);

namespace Ashokdubariya\Testimonial\Block;

use Ashokdubariya\Testimonial\Api\Data\TestimonialInterface;
use Ashokdubariya\Testimonial\Model\ResourceModel\Testimonial\Collection;
use Ashokdubariya\Testimonial\Model\ResourceModel\Testimonial\CollectionFactory;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

/**
 * Testimonial list block
 */
class TestimonialList extends Template
{
    /**
     * @param Context $context
     * @param CollectionFactory $collectionFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        private readonly CollectionFactory $collectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Get testimonials collection
     *
     * @return Collection
     */
    public function getTestimonials(): Collection
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('status', TestimonialInterface::STATUS_ENABLED)
            ->setOrder('created_at', 'DESC');
        
        return $collection;
    }

    /**
     * Get submit URL
     *
     * @return string
     */
    public function getSubmitUrl(): string
    {
        return $this->getUrl('testimonial/submit');
    }

    /**
     * Get rating stars HTML
     *
     * @param int $rating
     * @return string
     */
    public function getRatingStars(int $rating): string
    {
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $rating) {
                $stars .= '<span class="star filled">★</span>';
            } else {
                $stars .= '<span class="star">☆</span>';
            }
        }
        return $stars;
    }
}
