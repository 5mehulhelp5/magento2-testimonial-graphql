<?php
/**
 * Copyright © Ashokdubariya. All rights reserved.
 */

declare(strict_types=1);

namespace Ashokdubariya\Testimonial\Model;

use Ashokdubariya\Testimonial\Api\Data\TestimonialInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Testimonial model
 */
class Testimonial extends AbstractModel implements TestimonialInterface
{
    /**
     * Cache tag
     */
    public const CACHE_TAG = 'ashokdubariya_testimonial';

    /**
     * @var string
     */
    protected $_cacheTag = self::CACHE_TAG;

    /**
     * @var string
     */
    protected $_eventPrefix = 'ashokdubariya_testimonial';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(ResourceModel\Testimonial::class);
    }

    /**
     * @inheritDoc
     */
    public function getTestimonialId(): ?int
    {
        $id = $this->getData(self::TESTIMONIAL_ID);
        return $id ? (int)$id : null;
    }

    /**
     * @inheritDoc
     */
    public function setTestimonialId(int $testimonialId): TestimonialInterface
    {
        return $this->setData(self::TESTIMONIAL_ID, $testimonialId);
    }

    /**
     * @inheritDoc
     */
    public function getCustomerName(): string
    {
        return (string)$this->getData(self::CUSTOMER_NAME);
    }

    /**
     * @inheritDoc
     */
    public function setCustomerName(string $customerName): TestimonialInterface
    {
        return $this->setData(self::CUSTOMER_NAME, $customerName);
    }

    /**
     * @inheritDoc
     */
    public function getCustomerEmail(): string
    {
        return (string)$this->getData(self::CUSTOMER_EMAIL);
    }

    /**
     * @inheritDoc
     */
    public function setCustomerEmail(string $customerEmail): TestimonialInterface
    {
        return $this->setData(self::CUSTOMER_EMAIL, $customerEmail);
    }

    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        return (string)$this->getData(self::MESSAGE);
    }

    /**
     * @inheritDoc
     */
    public function setMessage(string $message): TestimonialInterface
    {
        return $this->setData(self::MESSAGE, $message);
    }

    /**
     * @inheritDoc
     */
    public function getRating(): int
    {
        return (int)$this->getData(self::RATING);
    }

    /**
     * @inheritDoc
     */
    public function setRating(int $rating): TestimonialInterface
    {
        return $this->setData(self::RATING, $rating);
    }

    /**
     * @inheritDoc
     */
    public function getStatus(): int
    {
        return (int)$this->getData(self::STATUS);
    }

    /**
     * @inheritDoc
     */
    public function setStatus(int $status): TestimonialInterface
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt(): ?string
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setCreatedAt(string $createdAt): TestimonialInterface
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * @inheritDoc
     */
    public function getUpdatedAt(): ?string
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setUpdatedAt(string $updatedAt): TestimonialInterface
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }
}
