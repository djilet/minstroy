<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class SlugValidator implements Rule
{
    private $table;
    private $condition;

    /**
     * Create a new rule instance.
     *
     * @param string $table
     * @param array  $condition
     *
     * @internal param int $id
     */
    public function __construct(string $table, array $condition = [])
    {
        $this->table = $table;
        $this->condition = $condition;
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
        $builder = DB::table($this->table)
            ->where($attribute, $value);
        
        if (!empty($this->condition)) {
            $builder->where($this->condition);
        }
        
        return $builder->count() == 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.unique');
    }
}
