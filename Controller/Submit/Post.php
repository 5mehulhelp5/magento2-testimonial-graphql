<?php
/**
 * Copyright © Ashokdubariya. All rights reserved.
 */

declare(strict_types=1);

namespace Ashokdubariya\Testimonial\Controller\Submit;

use Ashokdubariya\Testimonial\Api\Data\TestimonialInterface;
use Ashokdubariya\Testimonial\Api\Data\TestimonialInterfaceFactory;
use Ashokdubariya\Testimonial\Api\TestimonialRepositoryInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface;

/**
 * Submit form post controller
 */
class Post implements HttpPostActionInterface
{
    /**
     * @param RequestInterface $request
     * @param ResultFactory $resultFactory
     * @param Validator $formKeyValidator
     * @param ManagerInterface $messageManager
     * @param TestimonialInterfaceFactory $testimonialFactory
     * @param TestimonialRepositoryInterface $testimonialRepository
     */
    public function __construct(
        private readonly RequestInterface $request,
        private readonly ResultFactory $resultFactory,
        private readonly Validator $formKeyValidator,
        private readonly ManagerInterface $messageManager,
        private readonly TestimonialInterfaceFactory $testimonialFactory,
        private readonly TestimonialRepositoryInterface $testimonialRepository
    ) {
    }

    /**
     * Execute action
     *
     * @return Redirect
     */
    public function execute(): Redirect
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('*/*/');

        if (!$this->formKeyValidator->validate($this->request)) {
            $this->messageManager->addErrorMessage(__('Invalid form key. Please refresh the page.'));
            return $resultRedirect;
        }

        $data = $this->request->getPostValue();

        if (!$this->validateData($data)) {
            return $resultRedirect;
        }

        try {
            /** @var TestimonialInterface $testimonial */
            $testimonial = $this->testimonialFactory->create();
            $testimonial->setCustomerName((string)$data['customer_name']);
            $testimonial->setCustomerEmail((string)$data['customer_email']);
            $testimonial->setMessage((string)$data['message']);
            $testimonial->setRating((int)$data['rating']);
            $testimonial->setStatus(TestimonialInterface::STATUS_DISABLED); // Disabled by default

            $this->testimonialRepository->save($testimonial);
            $this->messageManager->addSuccessMessage(
                __('Thank you for your testimonial. It will be reviewed by our team.')
            );
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(
                __('An error occurred while submitting your testimonial. Please try again.')
            );
        }

        return $resultRedirect;
    }

    /**
     * Validate form data
     *
     * @param array|null $data
     * @return bool
     */
    private function validateData(?array $data): bool
    {
        if (!$data) {
            $this->messageManager->addErrorMessage(__('No data provided.'));
            return false;
        }

        if (empty($data['customer_name'])) {
            $this->messageManager->addErrorMessage(__('Name is required.'));
            return false;
        }

        if (empty($data['customer_email']) || !filter_var($data['customer_email'], FILTER_VALIDATE_EMAIL)) {
            $this->messageManager->addErrorMessage(__('Valid email is required.'));
            return false;
        }

        if (empty($data['message'])) {
            $this->messageManager->addErrorMessage(__('Message is required.'));
            return false;
        }

        $rating = (int)($data['rating'] ?? 0);
        if ($rating < 1 || $rating > 5) {
            $this->messageManager->addErrorMessage(__('Rating must be between 1 and 5.'));
            return false;
        }

        return true;
    }
}
