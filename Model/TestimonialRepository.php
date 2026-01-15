<?php
/**
 * Copyright © Ashokdubariya. All rights reserved.
 */

declare(strict_types=1);

namespace Ashokdubariya\Testimonial\Model;

use Ashokdubariya\Testimonial\Api\Data\TestimonialInterface;
use Ashokdubariya\Testimonial\Api\Data\TestimonialInterfaceFactory;
use Ashokdubariya\Testimonial\Api\Data\TestimonialSearchResultsInterface;
use Ashokdubariya\Testimonial\Api\Data\TestimonialSearchResultsInterfaceFactory;
use Ashokdubariya\Testimonial\Api\TestimonialRepositoryInterface;
use Ashokdubariya\Testimonial\Model\ResourceModel\Testimonial as TestimonialResource;
use Ashokdubariya\Testimonial\Model\ResourceModel\Testimonial\CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Testimonial repository implementation
 */
class TestimonialRepository implements TestimonialRepositoryInterface
{
    /**
     * @param TestimonialResource $resource
     * @param TestimonialInterfaceFactory $testimonialFactory
     * @param CollectionFactory $collectionFactory
     * @param TestimonialSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        private readonly TestimonialResource $resource,
        private readonly TestimonialInterfaceFactory $testimonialFactory,
        private readonly CollectionFactory $collectionFactory,
        private readonly TestimonialSearchResultsInterfaceFactory $searchResultsFactory,
        private readonly CollectionProcessorInterface $collectionProcessor
    ) {
    }

    /**
     * @inheritDoc
     */
    public function save(TestimonialInterface $testimonial): TestimonialInterface
    {
        try {
            $this->resource->save($testimonial);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save the testimonial: %1', $exception->getMessage()),
                $exception
            );
        }
        return $testimonial;
    }

    /**
     * @inheritDoc
     */
    public function getById(int $testimonialId): TestimonialInterface
    {
        $testimonial = $this->testimonialFactory->create();
        $this->resource->load($testimonial, $testimonialId);
        if (!$testimonial->getTestimonialId()) {
            throw new NoSuchEntityException(
                __('Testimonial with id "%1" does not exist.', $testimonialId)
            );
        }
        return $testimonial;
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria): TestimonialSearchResultsInterface
    {
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var TestimonialSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * @inheritDoc
     */
    public function delete(TestimonialInterface $testimonial): bool
    {
        try {
            $this->resource->delete($testimonial);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(
                __('Could not delete the testimonial: %1', $exception->getMessage()),
                $exception
            );
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById(int $testimonialId): bool
    {
        return $this->delete($this->getById($testimonialId));
    }
}
