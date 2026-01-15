<?php
/**
 * Copyright © Ashokdubariya. All rights reserved.
 */

declare(strict_types=1);

namespace Ashokdubariya\Testimonial\Model\Service;

use Ashokdubariya\Testimonial\Api\Data\TestimonialInterface;
use Ashokdubariya\Testimonial\Api\Data\TestimonialInterfaceFactory;
use Ashokdubariya\Testimonial\Api\TestimonialRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;

/**
 * Testimonial service for business logic
 */
class TestimonialService
{
    /**
     * @param TestimonialRepositoryInterface $testimonialRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param SortOrderBuilder $sortOrderBuilder
     * @param TestimonialInterfaceFactory $testimonialFactory
     */
    public function __construct(
        private readonly TestimonialRepositoryInterface $testimonialRepository,
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder,
        private readonly SortOrderBuilder $sortOrderBuilder,
        private readonly TestimonialInterfaceFactory $testimonialFactory
    ) {
    }

    /**
     * Get testimonials with pagination and filtering
     *
     * @param int $currentPage
     * @param int $pageSize
     * @param array|null $filters
     * @return array
     * @throws LocalizedException
     */
    public function getTestimonials(int $currentPage, int $pageSize, ?array $filters = null): array
    {
        $sortOrder = $this->sortOrderBuilder
            ->setField('created_at')
            ->setDirection('DESC')
            ->create();

        $this->searchCriteriaBuilder
            ->setCurrentPage($currentPage)
            ->setPageSize($pageSize)
            ->addSortOrder($sortOrder);

        // Apply filters if provided
        if ($filters) {
            $this->applyFilters($filters);
        } else {
            // Default: only enabled testimonials if no filters specified
            $this->searchCriteriaBuilder->addFilter('status', TestimonialInterface::STATUS_ENABLED, 'eq');
        }

        $searchCriteria = $this->searchCriteriaBuilder->create();
        $searchResults = $this->testimonialRepository->getList($searchCriteria);

        return [
            'total_count' => $searchResults->getTotalCount(),
            'items' => $searchResults->getItems()
        ];
    }

    /**
     * Get enabled testimonials with pagination (backward compatibility)
     *
     * @param int $currentPage
     * @param int $pageSize
     * @return array
     * @throws LocalizedException
     */
    public function getEnabledTestimonials(int $currentPage, int $pageSize): array
    {
        return $this->getTestimonials($currentPage, $pageSize);
    }

    /**
     * Apply filters to search criteria
     *
     * @param array $filters
     * @return void
     */
    private function applyFilters(array $filters): void
    {
        foreach ($filters as $field => $filter) {
            if (isset($filter['eq'])) {
                $this->searchCriteriaBuilder->addFilter($field, $filter['eq'], 'eq');
            }
            if (isset($filter['in']) && is_array($filter['in'])) {
                $this->searchCriteriaBuilder->addFilter($field, $filter['in'], 'in');
            }
            if (isset($filter['like'])) {
                $this->searchCriteriaBuilder->addFilter($field, '%' . $filter['like'] . '%', 'like');
            }
            if (isset($filter['from'])) {
                $this->searchCriteriaBuilder->addFilter($field, $filter['from'], 'gteq');
            }
            if (isset($filter['to'])) {
                $this->searchCriteriaBuilder->addFilter($field, $filter['to'], 'lteq');
            }
        }
    }

    /**
     * Get testimonial by ID
     *
     * @param int $id
     * @return TestimonialInterface
     * @throws LocalizedException
     */
    public function getTestimonialById(int $id): TestimonialInterface
    {
        return $this->testimonialRepository->getById($id);
    }

    /**
     * Add new testimonial
     *
     * @param array $input
     * @return array
     * @throws GraphQlInputException
     */
    public function addTestimonial(array $input): array
    {
        $this->validateInput($input);

        try {
            /** @var TestimonialInterface $testimonial */
            $testimonial = $this->testimonialFactory->create();
            $testimonial->setCustomerName($input['customer_name']);
            $testimonial->setCustomerEmail($input['customer_email']);
            $testimonial->setMessage($input['message']);
            $testimonial->setRating((int)$input['rating']);
            $testimonial->setStatus(TestimonialInterface::STATUS_DISABLED);

            $savedTestimonial = $this->testimonialRepository->save($testimonial);

            return [
                'testimonial_id' => $savedTestimonial->getTestimonialId(),
                'status' => 'success',
                'message' => 'Testimonial submitted successfully. It will be reviewed by our team.'
            ];
        } catch (\Exception $e) {
            throw new GraphQlInputException(__('Unable to save testimonial: %1', $e->getMessage()));
        }
    }

    /**
     * Update existing testimonial
     *
     * @param int $id
     * @param array $input
     * @return array
     * @throws GraphQlInputException
     * @throws GraphQlNoSuchEntityException
     */
    public function updateTestimonial(int $id, array $input): array
    {
        try {
            $testimonial = $this->testimonialRepository->getById($id);

            // Update only provided fields
            if (isset($input['customer_name'])) {
                $testimonial->setCustomerName($input['customer_name']);
            }
            if (isset($input['customer_email'])) {
                if (!filter_var($input['customer_email'], FILTER_VALIDATE_EMAIL)) {
                    throw new GraphQlInputException(__('Invalid email format.'));
                }
                $testimonial->setCustomerEmail($input['customer_email']);
            }
            if (isset($input['message'])) {
                $testimonial->setMessage($input['message']);
            }
            if (isset($input['rating'])) {
                $rating = (int)$input['rating'];
                if ($rating < 1 || $rating > 5) {
                    throw new GraphQlInputException(__('Rating must be between 1 and 5.'));
                }
                $testimonial->setRating($rating);
            }
            if (isset($input['status'])) {
                $testimonial->setStatus((int)$input['status']);
            }

            $updatedTestimonial = $this->testimonialRepository->save($testimonial);

            return [
                'testimonial_id' => $updatedTestimonial->getTestimonialId(),
                'status' => 'success',
                'message' => 'Testimonial updated successfully.',
                'testimonial' => $updatedTestimonial
            ];
        } catch (NoSuchEntityException $e) {
            throw new GraphQlNoSuchEntityException(__('Testimonial with ID %1 not found.', $id));
        } catch (\Exception $e) {
            throw new GraphQlInputException(__('Unable to update testimonial: %1', $e->getMessage()));
        }
    }

    /**
     * Delete testimonial
     *
     * @param int $id
     * @return array
     * @throws GraphQlInputException
     * @throws GraphQlNoSuchEntityException
     */
    public function deleteTestimonial(int $id): array
    {
        try {
            $this->testimonialRepository->deleteById($id);

            return [
                'status' => 'success',
                'message' => 'Testimonial deleted successfully.'
            ];
        } catch (NoSuchEntityException $e) {
            throw new GraphQlNoSuchEntityException(__('Testimonial with ID %1 not found.', $id));
        } catch (\Exception $e) {
            throw new GraphQlInputException(__('Unable to delete testimonial: %1', $e->getMessage()));
        }
    }

    /**
     * Validate input data
     *
     * @param array $input
     * @return void
     * @throws GraphQlInputException
     */
    private function validateInput(array $input): void
    {
        if (empty($input['customer_name'])) {
            throw new GraphQlInputException(__('Customer name is required.'));
        }

        if (empty($input['customer_email'])) {
            throw new GraphQlInputException(__('Customer email is required.'));
        }

        if (!filter_var($input['customer_email'], FILTER_VALIDATE_EMAIL)) {
            throw new GraphQlInputException(__('Invalid email format.'));
        }

        if (empty($input['message'])) {
            throw new GraphQlInputException(__('Message is required.'));
        }

        $rating = (int)($input['rating'] ?? 0);
        if ($rating < 1 || $rating > 5) {
            throw new GraphQlInputException(__('Rating must be between 1 and 5.'));
        }
    }
}
