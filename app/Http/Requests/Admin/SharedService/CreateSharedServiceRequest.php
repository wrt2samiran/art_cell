<?php

namespace App\Http\Requests\Admin\SharedService;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\SharedService;
class CreateSharedServiceRequest extends FormRequest
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
        $rules_array =[
        'name'          => 'required|min:2|max:255|unique:'.(new SharedService)->getTable().',name',
        ];

        if(request()->is_sharing){
            $rules_array['number_of_days']='required|numeric';
            $rules_array['price']='required|numeric';
            $rules_array['extra_price_per_day']='required|numeric';
        }
        if(request()->is_selling){
            $rules_array['selling_price']='required|numeric';
        }

        return $rules_array;
    }
    public function messages(){
        return  [
                    'name.required'                => 'Please enter name',
                    'name.min'                     => 'Name should be should be at least 2 characters',
                    'name.max'                     => 'Name should not be more than 255 characters',
                ];

    }

}
