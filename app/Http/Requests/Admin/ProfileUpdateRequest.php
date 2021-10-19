<?php

namespace App\Http\Requests\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ProfileUpdateRequest extends FormRequest
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
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'min:5|nullable',
            'password_old' => 'min:5|nullable',
        ];
    }

    public function ifChangePassword(Admin $profile)
    {
        $password = $this->input('password_old');
        $password_new = $this->input('password');
        
        if (! empty($password) and !empty($password_new)) {

            if (!Hash::check($password, $profile->getAuthPassword())) {
                $validator = $this->getValidatorInstance();
                $messageBag = $validator->getMessageBag();
                $messageBag->add('password_old', Lang::get('validation.password'));
                $this->failedValidation($validator);
            }

            Validator::make($this->all(), [
                'password' => 'required|min:5|confirmed',
            ])->validate();
        }
    }

    public function updateAttributes()
    {
        $attr = [
            'name' => $this->input('name'),
            'email' => $this->input('email'),
            'phone' => $this->input('phone'),
        ];
        
        $password = $this->input('password');
        if (! empty($password)) {
            $attr['password'] = Hash::make($password);
        }
        
        return $attr;
    }
}
