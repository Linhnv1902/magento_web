<?php

declare(strict_types=1);

namespace Linhnv\ReviewApi\Validation;

/**
 * ValidationResult object supposed to be created by dedicated validator service which makes a validation and checks
 * whether all entity invariants (business rules that always should be fulfilled) are valid.
 *
 * ValidationResult represents a container storing all the validation errors that happened during the entity validation.
 *
 * @see \Magento\Framework\Validation\ValidationResult in Magento 2.3
 */
class ValidationResult
{
    /**
     * @var array
     */
    private $errors = [];

    /**
     * ValidationResult constructor.
     *
     * @param array $errors
     */
    public function __construct(array $errors)
    {
        $this->errors = $errors;
    }

    /**
     * Check if Result is Valid
     *
     * @return bool
     */
    public function isValid(): bool
    {
        return empty($this->errors);
    }

    /**
     * Get Errors
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
