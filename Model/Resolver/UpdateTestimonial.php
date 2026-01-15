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
 * Update testimonial mutation resolver
 */
class UpdateTestimonial implements ResolverInterface
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

        $input = $args['input'] ?? [];
        $result = $this->testimonialService->updateTestimonial((int)$args['id'], $input);

        // Format testimonial data if present
        if (isset($result['testimonial'])) {
            $testimonial = $result['testimonial'];
            $result['testimonial'] = [
                'testimonial_id' => $testimonial->getTestimonialId(),
                'customer_name' => $testimonial->getCustomerName(),
                'customer_email' => $testimonial->getCustomerEmail(),
                'message' => $testimonial->getMessage(),
                'rating' => $testimonial->getRating(),
                'status' => $testimonial->getStatus(),
                'created_at' => $testimonial->getCreatedAt(),
                'updated_at' => $testimonial->getUpdatedAt()
            ];
        }

        return $result;
    }
}
