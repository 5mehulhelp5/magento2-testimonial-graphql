<?php
/**
 * Copyright © Ashokdubariya. All rights reserved.
 */

declare(strict_types=1);

namespace Ashokdubariya\Testimonial\Controller\Adminhtml\Testimonial;

use Ashokdubariya\Testimonial\Api\TestimonialRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

/**
 * Edit controller
 */
class Edit extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level
     */
    public const ADMIN_RESOURCE = 'Ashokdubariya_Testimonial::save';

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param TestimonialRepositoryInterface $testimonialRepository
     */
    public function __construct(
        Context $context,
        private readonly PageFactory $resultPageFactory,
        private readonly TestimonialRepositoryInterface $testimonialRepository
    ) {
        parent::__construct($context);
    }

    /**
     * Execute action
     *
     * @return Page|\Magento\Backend\Model\View\Result\Redirect
     */
    public function execute(): Page|\Magento\Backend\Model\View\Result\Redirect
    {
        $id = (int)$this->getRequest()->getParam('testimonial_id');
        
        if ($id) {
            try {
                $this->testimonialRepository->getById($id);
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage(__('This testimonial no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Ashokdubariya_Testimonial::testimonial');
        $resultPage->getConfig()->getTitle()->prepend(
            $id ? __('Edit Testimonial') : __('New Testimonial')
        );

        return $resultPage;
    }
}
