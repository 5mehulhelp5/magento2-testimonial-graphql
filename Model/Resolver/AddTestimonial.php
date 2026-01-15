<?php
/**
 * Copyright © Ashokdubariya. All rights reserved.
 */

declare(strict_types=1);

namespace Ashokdubariya\Testimonial\Model\Resolver;

use Ashokdubariya\Testimonial\Model\Service\TestimonialService;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

/**
 * Add testimonial mutation resolver
 */
class AddTestimonial implements ResolverInterface
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
        $input = $args['input'] ?? [];
        return $this->testimonialService->addTestimonial($input);
    }
}
