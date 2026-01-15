<?php
/**
 * Copyright © Ashokdubariya. All rights reserved.
 */

declare(strict_types=1);

namespace Ashokdubariya\Testimonial\Controller\Adminhtml\Testimonial;

use Ashokdubariya\Testimonial\Api\Data\TestimonialInterfaceFactory;
use Ashokdubariya\Testimonial\Api\TestimonialRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Save controller
 */
class Save extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level
     */
    public const ADMIN_RESOURCE = 'Ashokdubariya_Testimonial::save';

    /**
     * @param Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param TestimonialInterfaceFactory $testimonialFactory
     * @param TestimonialRepositoryInterface $testimonialRepository
     */
    public function __construct(
        Context $context,
        private readonly DataPersistorInterface $dataPersistor,
        private readonly TestimonialInterfaceFactory $testimonialFactory,
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
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if (!$data) {
            return $resultRedirect->setPath('*/*/');
        }

        $id = (int)($this->getRequest()->getParam('testimonial_id') ?? 0);

        try {
            if ($id) {
                $testimonial = $this->testimonialRepository->getById($id);
            } else {
                $testimonial = $this->testimonialFactory->create();
            }

            // Validate all fields
            $this->validateCustomerName((string)$data['customer_name']);
            $this->validateEmail((string)$data['customer_email']);
            $this->validateMessage((string)$data['message']);
            $this->validateRating((int)$data['rating']);
            $this->validateStatus((int)$data['status']);

            $testimonial->setCustomerName((string)$data['customer_name']);
            $testimonial->setCustomerEmail((string)$data['customer_email']);
            $testimonial->setMessage((string)$data['message']);
            $testimonial->setRating((int)$data['rating']);
            $testimonial->setStatus((int)$data['status']);

            $this->testimonialRepository->save($testimonial);
            $this->messageManager->addSuccessMessage(__('You saved the testimonial.'));
            $this->dataPersistor->clear('ashokdubariya_testimonial');

            if ($this->getRequest()->getParam('back')) {
                return $resultRedirect->setPath('*/*/edit', [
                    'testimonial_id' => $testimonial->getTestimonialId(),
                    '_current' => true
                ]);
            }

            return $resultRedirect->setPath('*/*/');
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage(
                $e,
                __('Something went wrong while saving the testimonial.')
            );
        }

        $this->dataPersistor->set('ashokdubariya_testimonial', $data);
        return $resultRedirect->setPath('*/*/edit', [
            'testimonial_id' => $id
        ]);
    }

    /**
     * Validate customer name
     *
     * @param string $name
     * @return void
     * @throws LocalizedException
     */
    private function validateCustomerName(string $name): void
    {
        if (empty(trim($name))) {
            throw new LocalizedException(__('Customer name is required.'));
        }

        if (strlen($name) > 255) {
            throw new LocalizedException(__('Customer name cannot exceed 255 characters.'));
        }
    }

    /**
     * Validate email
     *
     * @param string $email
     * @return void
     * @throws LocalizedException
     */
    private function validateEmail(string $email): void
    {
        if (empty(trim($email))) {
            throw new LocalizedException(__('Email is required.'));
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new LocalizedException(__('Please enter a valid email address.'));
        }

        if (strlen($email) > 255) {
            throw new LocalizedException(__('Email cannot exceed 255 characters.'));
        }
    }

    /**
     * Validate rating
     *
     * @param int $rating
     * @return void
     * @throws LocalizedException
     */
    private function validateRating(int $rating): void
    {
        if ($rating < 1 || $rating > 5) {
            throw new LocalizedException(__('Rating must be between 1 and 5 stars.'));
        }
    }

    /**
     * Validate status
     *
     * @param int $status
     * @return void
     * @throws LocalizedException
     */
    private function validateStatus(int $status): void
    {
        if (!in_array($status, [0, 1], true)) {
            throw new LocalizedException(__('Status must be either Enabled or Disabled.'));
        }
    }

    /**
     * Validate message
     *
     * @param string $message
     * @return void
     * @throws LocalizedException
     */
    private function validateMessage(string $message): void
    {
        if (empty(trim($message))) {
            throw new LocalizedException(__('Testimonial message is required.'));
        }

        if (strlen($message) < 10) {
            throw new LocalizedException(__('Testimonial message must be at least 10 characters long.'));
        }
    }
}
