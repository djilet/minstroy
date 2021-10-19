<?php

namespace App\Http\Requests\Admin;

use App\Rules\SlugValidator;
use Illuminate\Foundation\Http\FormRequest;

class PageUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'content' => 'nullable',
            'slug' =>  [
                'required',
                new SlugValidator('pages', [
                    ['id', '<>', $this->route('id')],
                    'parent_id' => $this->input('parent_id'),
                    'lang' => editor_lang(),
                ])
            ],
            'active' => 'boolean',
            'parent_id' => 'numeric|nullable',
        ];
    }
}
