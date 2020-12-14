<?php

namespace App\Http\Requests\Admin\SparePart;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\SparePart;
class CreateSparePartRequest extends FormRequest
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
            'name'=> 'required|min:2|max:255|unique:'.(new SparePart)->getTable().',name',
            'manufacturer'    => 'required|min:2|max:255',
            'unit_master_id'  => 'required',
            'price'     => 'required'
        ];
    }

    public function messages(){
        return[
                'name.required'                => 'Please enter name',
                'name.min'                     => 'Name should be should be at least 2 characters',
                'name.max'                     => 'Name should not be more than 255 characters',
                'manufacturer.required'        => 'Please enter Manufacturer name',
                'manufacturer.min'             => 'Manufacturer name should be at least 2 characters',
                'manufacturer.max'             => 'Manufacturer name should not be more than 255 characters',
                'unit_master_id.required'      => 'Unit is required',
                'price.required'               => 'Price is required',
            ];
    }
}
