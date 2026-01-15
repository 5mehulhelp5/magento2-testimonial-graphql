<?php
/**
 * Copyright © Ashokdubariya. All rights reserved.
 */

declare(strict_types=1);

namespace Ashokdubariya\Testimonial\Controller\Adminhtml\Testimonial;

use Ashokdubariya\Testimonial\Api\TestimonialRepositoryInterface;
use Ashokdubariya\Testimonial\Model\ResourceModel\Testimonial\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;

/**
 * Mass status controller
 */
class MassStatus extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level
     */
    public const ADMIN_RESOURCE = 'Ashokdubariya_Testimonial::save';

    /**
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param TestimonialRepositoryInterface $testimonialRepository
     */
    public function __construct(
        Context $context,
        private readonly Filter $filter,
        private readonly CollectionFactory $collectionFactory,
        private readonly TestimonialRepositoryInterface $testimonialRepository
    ) {
        parent::__construct($context);
    }

    /**
     * Execute action
     *
     * @return Redirect
     */
    public function execute(): Redirect
    {
        try {
            $collection = $this->filter->getCollection($this->collectionFactory->create());
            $status = (int)$this->getRequest()->getParam('status');
            
            // Validate status value
            if (!in_array($status, [0, 1], true)) {
                throw new LocalizedException(__('Invalid status value. Status must be either 0 or 1.'));
            }

            $collectionSize = $collection->getSize();
            $updatedCount = 0;

            foreach ($collection as $testimonial) {
                try {
                    $testimonial->setStatus($status);
                    $this->testimonialRepository->save($testimonial);
                    $updatedCount++;
                } catch (\Exception $e) {
                    $this->messageManager->addErrorMessage(
                        __('Error updating testimonial ID %1: %2', $testimonial->getTestimonialId(), $e->getMessage())
                    );
                }
            }

            if ($updatedCount > 0) {
                $this->messageManager->addSuccessMessage(
                    __('A total of %1 record(s) have been updated.', $updatedCount)
                );
            }
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage(
                $e,
                __('Something went wrong while updating testimonials.')
            );
        }

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
