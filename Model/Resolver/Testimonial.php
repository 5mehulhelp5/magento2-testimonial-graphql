<?php
/**
 * Copyright © Ashokdubariya. All rights reserved.
 */

declare(strict_types=1);

namespace Ashokdubariya\Testimonial\Model\Resolver;

use Ashokdubariya\Testimonial\Model\Service\TestimonialService;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

/**
 * Testimonial query resolver
 */
class Testimonial implements ResolverInterface
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
        if (!isset($args['id']) || $args['id'] < 1) {
            throw new GraphQlInputException(__('Testimonial ID is required and must be greater than 0.'));
        }

        try {
            $testimonial = $this->testimonialService->getTestimonialById((int)$args['id']);

            return [
                'testimonial_id' => $testimonial->getTestimonialId(),
                'customer_name' => $testimonial->getCustomerName(),
                'customer_email' => $testimonial->getCustomerEmail(),
                'message' => $testimonial->getMessage(),
                'rating' => $testimonial->getRating(),
                'status' => $testimonial->getStatus(),
                'created_at' => $testimonial->getCreatedAt()
            ];
        } catch (NoSuchEntityException $e) {
            throw new GraphQlNoSuchEntityException(__('Testimonial with ID %1 not found.', $args['id']));
        }
    }
}
