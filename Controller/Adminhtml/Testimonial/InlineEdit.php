<?php
/**
 * Copyright © Ashokdubariya. All rights reserved.
 */

declare(strict_types=1);

namespace Ashokdubariya\Testimonial\Controller\Adminhtml\Testimonial;

use Ashokdubariya\Testimonial\Api\TestimonialRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Inline edit controller for testimonial grid
 */
class InlineEdit extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level
     */
    public const ADMIN_RESOURCE = 'Ashokdubariya_Testimonial::save';

    /**
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param TestimonialRepositoryInterface $testimonialRepository
     */
    public function __construct(
        Context $context,
        private readonly JsonFactory $jsonFactory,
        private readonly TestimonialRepositoryInterface $testimonialRepository
    ) {
        parent::__construct($context);
    }

    /**
     * Execute inline edit action
     *
     * @return Json
     */
    public function execute(): Json
    {
        /** @var Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        $postItems = $this->getRequest()->getParam('items', []);
        if (!($this->getRequest()->getParam('isAjax') && count($postItems))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error' => true,
            ]);
        }

        foreach (array_keys($postItems) as $testimonialId) {
            try {
                $testimonial = $this->testimonialRepository->getById((int)$testimonialId);
                $testimonialData = $postItems[$testimonialId];

                // Validate and set data
                if (isset($testimonialData['customer_name'])) {
                    $this->validateCustomerName($testimonialData['customer_name']);
                    $testimonial->setCustomerName((string)$testimonialData['customer_name']);
                }

                if (isset($testimonialData['customer_email'])) {
                    $this->validateEmail($testimonialData['customer_email']);
                    $testimonial->setCustomerEmail((string)$testimonialData['customer_email']);
                }

                if (isset($testimonialData['rating'])) {
                    $this->validateRating((int)$testimonialData['rating']);
                    $testimonial->setRating((int)$testimonialData['rating']);
                }

                if (isset($testimonialData['status'])) {
                    $this->validateStatus((int)$testimonialData['status']);
                    $testimonial->setStatus((int)$testimonialData['status']);
                }

                if (isset($testimonialData['message'])) {
                    $this->validateMessage($testimonialData['message']);
                    $testimonial->setMessage((string)$testimonialData['message']);
                }

                $this->testimonialRepository->save($testimonial);
            } catch (NoSuchEntityException $e) {
                $messages[] = __('Testimonial with ID "%1" does not exist.', $testimonialId);
                $error = true;
            } catch (LocalizedException $e) {
                $messages[] = __('[Testimonial ID: %1] %2', $testimonialId, $e->getMessage());
                $error = true;
            } catch (\Exception $e) {
                $messages[] = __(
                    '[Testimonial ID: %1] Something went wrong while saving the testimonial.',
                    $testimonialId
                );
                $error = true;
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
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
            throw new LocalizedException(__('Customer name cannot be empty.'));
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
            throw new LocalizedException(__('Email cannot be empty.'));
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
            throw new LocalizedException(__('Rating must be between 1 and 5.'));
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
            throw new LocalizedException(__('Status must be either 0 (Disabled) or 1 (Enabled).'));
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
            throw new LocalizedException(__('Testimonial message cannot be empty.'));
        }
    }
}
