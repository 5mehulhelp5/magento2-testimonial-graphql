<?php
/**
 * Copyright © Ashokdubariya. All rights reserved.
 */

declare(strict_types=1);

namespace Ashokdubariya\Testimonial\Model\Resolver;

use Ashokdubariya\Testimonial\Model\Service\TestimonialService;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

/**
 * Testimonials query resolver
 */
class Testimonials implements ResolverInterface
{
    /**
     * @param TestimonialService $testimonialService
     */
    public function __construct(
        private readonly TestimonialService $testimonialService
    ) {
    }

    /**
     * @inheritDoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ): array {
        $currentPage = $args['currentPage'] ?? 1;
        $pageSize = $args['pageSize'] ?? 10;
        $filter = $args['filter'] ?? null;

        if ($currentPage < 1) {
            throw new GraphQlInputException(__('currentPage must be greater than 0.'));
        }

        if ($pageSize < 1) {
            throw new GraphQlInputException(__('pageSize must be greater than 0.'));
        }

        $result = $this->testimonialService->getTestimonials($currentPage, $pageSize, $filter);

        return [
            'total_count' => $result['total_count'],
            'items' => array_map(function ($testimonial) {
                return [
                    'testimonial_id' => $testimonial->getTestimonialId(),
                    'customer_name' => $testimonial->getCustomerName(),
                    'customer_email' => $testimonial->getCustomerEmail(),
                    'message' => $testimonial->getMessage(),
                    'rating' => $testimonial->getRating(),
                    'status' => $testimonial->getStatus(),
                    'created_at' => $testimonial->getCreatedAt(),
                    'updated_at' => $testimonial->getUpdatedAt()
                ];
            }, $result['items'])
        ];
    }
}
