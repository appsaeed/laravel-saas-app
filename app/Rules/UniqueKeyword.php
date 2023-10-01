<?php

namespace App\Rules;

use App\Models\Keywords;
use Illuminate\Contracts\Validation\Rule;

class UniqueKeyword implements Rule
{

    protected $keyword;
    protected $user_id;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($keyword, $user_id)
    {
        $this->keyword = $keyword;
        $this->user_id = $user_id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $return = Keywords::where('user_id', $this->user_id)->where('keyword_name', $this->keyword)->first();
        if ($return) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return __('locale.keywords.keyword_availability', ['keyword' => $this->keyword]);
    }
}
