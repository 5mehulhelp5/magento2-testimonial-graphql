<?php
/**
 * Copyright © Ashokdubariya. All rights reserved.
 */

declare(strict_types=1);

namespace Ashokdubariya\Testimonial\Block\Adminhtml\Testimonial\Edit;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Delete button
 */
class DeleteButton implements ButtonProviderInterface
{
    /**
     * @param RequestInterface $request
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        private readonly RequestInterface $request,
        private readonly UrlInterface $urlBuilder
    ) {
    }

    /**
     * @return array
     */
    public function getButtonData(): array
    {
        $data = [];
        $testimonialId = (int)$this->request->getParam('testimonial_id');
        
        if ($testimonialId) {
            $data = [
                'label' => __('Delete'),
                'class' => 'delete',
                'on_click' => sprintf(
                    "deleteConfirm('%s', '%s')",
                    __('Are you sure you want to delete this testimonial?'),
                    $this->urlBuilder->getUrl('*/*/delete', ['testimonial_id' => $testimonialId])
                ),
                'sort_order' => 20
            ];
        }
        
        return $data;
    }
}
