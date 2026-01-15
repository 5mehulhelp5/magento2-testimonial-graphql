<?php
/**
 * Copyright © Ashokdubariya. All rights reserved.
 */

declare(strict_types=1);

namespace Ashokdubariya\Testimonial\Model;

use Ashokdubariya\Testimonial\Api\Data\TestimonialSearchResultsInterface;
use Magento\Framework\Api\SearchResults;

/**
 * Testimonial search results implementation
 */
class TestimonialSearchResults extends SearchResults implements TestimonialSearchResultsInterface
{
}
