<?php
/**
 * Copyright © Ashokdubariya. All rights reserved.
 */

declare(strict_types=1);

namespace Ashokdubariya\Testimonial\ViewModel;

use Ashokdubariya\Testimonial\Api\Data\TestimonialInterface;
use Ashokdubariya\Testimonial\Model\ResourceModel\Testimonial\Collection;
use Ashokdubariya\Testimonial\Model\ResourceModel\Testimonial\CollectionFactory;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * ViewModel for Hyva testimonial list
 */
class TestimonialList implements ArgumentInterface
{
    /**
     * @param CollectionFactory $collectionFactory
     * @param UrlInterface $urlBuilder
     * @param Json $jsonSerializer
     */
    public function __construct(
        private readonly CollectionFactory $collectionFactory,
        private readonly UrlInterface $urlBuilder,
        private readonly Json $jsonSerializer
    ) {
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
     * Get testimonials as JSON for Alpine.js
     *
     * @return string
     */
    public function getTestimonialsJson(): string
    {
        $testimonials = [];
        foreach ($this->getTestimonials() as $testimonial) {
            $testimonials[] = [
                'id' => $testimonial->getTestimonialId(),
                'customer_name' => $testimonial->getCustomerName(),
                'customer_email' => $testimonial->getCustomerEmail(),
                'message' => $testimonial->getMessage(),
                'rating' => (int)$testimonial->getRating(),
                'created_at' => $testimonial->getCreatedAt(),
            ];
        }
        
        return $this->jsonSerializer->serialize($testimonials);
    }

    /**
     * Get submit URL
     *
     * @return string
     */
    public function getSubmitUrl(): string
    {
        return $this->urlBuilder->getUrl('testimonial/submit');
    }

    /**
     * Get total testimonials count
     *
     * @return int
     */
    public function getTotalCount(): int
    {
        return $this->getTestimonials()->getSize();
    }

    /**
     * Format date for display
     *
     * @param string $date
     * @return string
     */
    public function formatDate(string $date): string
    {
        $timestamp = strtotime($date);
        return date('F j, Y', $timestamp);
    }

    /**
     * Get rating stars array
     *
     * @param int $rating
     * @return array
     */
    public function getRatingStars(int $rating): array
    {
        $stars = [];
        for ($i = 1; $i <= 5; $i++) {
            $stars[] = [
                'filled' => $i <= $rating,
                'index' => $i
            ];
        }
        return $stars;
    }

    /**
     * Get rating stars as HTML string (for standard Luma template)
     *
     * @param int $rating
     * @return string
     */
    public function getRatingStarsHtml(int $rating): string
    {
        // Ensure rating stays between 0–5
        $rating = max(0, min(5, $rating));

        $percentage = ($rating / 5) * 100;

        $html  = '<div class="rating-summary">';
        $html .= '    <div class="rating-result" title="' . $percentage . '%">';
        $html .= '        <span style="width:' . $percentage . '%">';
        $html .= '            <span>' . $percentage . '%</span>';
        $html .= '        </span>';
        $html .= '    </div>';
        $html .= '</div>';

        return $html;
    }
}
