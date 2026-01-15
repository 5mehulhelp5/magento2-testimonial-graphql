<?php
/**
 * Copyright © Ashokdubariya. All rights reserved.
 */

declare(strict_types=1);

namespace Ashokdubariya\Testimonial\Api;

use Ashokdubariya\Testimonial\Api\Data\TestimonialInterface;
use Ashokdubariya\Testimonial\Api\Data\TestimonialSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Testimonial repository interface
 * @api
 */
interface TestimonialRepositoryInterface
{
    /**
     * Save testimonial
     *
     * @param TestimonialInterface $testimonial
     * @return TestimonialInterface
     * @throws CouldNotSaveException
     */
    public function save(TestimonialInterface $testimonial): TestimonialInterface;

    /**
     * Get testimonial by ID
     *
     * @param int $testimonialId
     * @return TestimonialInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $testimonialId): TestimonialInterface;

    /**
     * Get testimonials list
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return TestimonialSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): TestimonialSearchResultsInterface;

    /**
     * Delete testimonial
     *
     * @param TestimonialInterface $testimonial
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(TestimonialInterface $testimonial): bool;

    /**
     * Delete testimonial by ID
     *
     * @param int $testimonialId
     * @return bool
     * @throws NoSuchEntityException
     * @throws CouldNotDeleteException
     */
    public function deleteById(int $testimonialId): bool;
}
