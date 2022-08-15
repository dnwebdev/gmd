<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MoneyValidation implements Rule
{
    /**
     * Minimal money
     * @var integer
     */
    protected $min = 0;

    /**
     * Maximal money
     * @var integer|null
     */
    protected $max = null;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($min = 0, $max = null)
    {
        $this->min = $min;
        $this->max = $max;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $value = (int) str_replace(',', '', $value);

        if ($value <= $this->min) {
            return false;
        }

        if ($value >= $this->max) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        if (is_null($this->max)) {
            return trans('validation.min.numeric', ['min' => $this->min]);
        }

        return trans('validation.between.numeric', ['min' => $this->min, 'max' => $this->max]);
    }
}
