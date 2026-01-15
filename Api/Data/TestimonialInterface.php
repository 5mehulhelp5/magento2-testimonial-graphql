<?php
/**
 * Copyright © Ashokdubariya. All rights reserved.
 */

declare(strict_types=1);

namespace Ashokdubariya\Testimonial\Api\Data;

/**
 * Testimonial interface
 * @api
 */
interface TestimonialInterface
{
    /**
     * Constants for keys of data array
     */
    public const TESTIMONIAL_ID = 'testimonial_id';
    public const CUSTOMER_NAME = 'customer_name';
    public const CUSTOMER_EMAIL = 'customer_email';
    public const MESSAGE = 'message';
    public const RATING = 'rating';
    public const STATUS = 'status';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';

    /**
     * Status constants
     */
    public const STATUS_DISABLED = 0;
    public const STATUS_ENABLED = 1;

    /**
     * Get testimonial ID
     *
     * @return int|null
     */
    public function getTestimonialId(): ?int;

    /**
     * Set testimonial ID
     *
     * @param int $testimonialId
     * @return $this
     */
    public function setTestimonialId(int $testimonialId): self;

    /**
     * Get customer name
     *
     * @return string
     */
    public function getCustomerName(): string;

    /**
     * Set customer name
     *
     * @param string $customerName
     * @return $this
     */
    public function setCustomerName(string $customerName): self;

    /**
     * Get customer email
     *
     * @return string
     */
    public function getCustomerEmail(): string;

    /**
     * Set customer email
     *
     * @param string $customerEmail
     * @return $this
     */
    public function setCustomerEmail(string $customerEmail): self;

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage(): string;

    /**
     * Set message
     *
     * @param string $message
     * @return $this
     */
    public function setMessage(string $message): self;

    /**
     * Get rating
     *
     * @return int
     */
    public function getRating(): int;

    /**
     * Set rating
     *
     * @param int $rating
     * @return $this
     */
    public function setRating(int $rating): self;

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus(): int;

    /**
     * Set status
     *
     * @param int $status
     * @return $this
     */
    public function setStatus(int $status): self;

    /**
     * Get created at
     *
     * @return string|null
     */
    public function getCreatedAt(): ?string;

    /**
     * Set created at
     *
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt(string $createdAt): self;

    /**
     * Get updated at
     *
     * @return string|null
     */
    public function getUpdatedAt(): ?string;

    /**
     * Set updated at
     *
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt(string $updatedAt): self;
}
