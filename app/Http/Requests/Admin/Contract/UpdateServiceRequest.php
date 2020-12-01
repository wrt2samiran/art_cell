<?php

namespace App\Http\Requests\Admin\Contract;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\ContractService;
class UpdateServiceRequest extends FormRequest
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
        
        $contract_service_id=request()->route('contract_service_id');
        $contract_service=ContractService::findOrFail($contract_service_id);

        $rules_array=[
            'service'=>'required'
        ];
        if($contract_service->service_type!='Maintenance'){
            $rules_array['service_type']='required';
        }
        if(!request()->service_type=='Free'){
            $rules_array['service_price']='required|numeric';
        }
        if(request()->service_type=='Maintenance'){

            $rules_array['interval_type']='required';
            $rules_array['reccure_every']='required';

                        
            if(request()->interval_type=='daily'){
                $rules_array['number_of_times']='required';
            }


            $rules_array['start_date']='required';

            $rules_array['end_by_or_after']='required';

            if(request()->end_by_or_after=='end_by'){
                $rules_array['end_date']='required';
            }elseif (request()->end_by_or_after=='end_after') {
                $rules_array['no_of_occurrences']='required|numeric|min:1';
            }

            if(request()->interval_type=='weekly'){
                $rules_array['weekly_days']='required';
            }

            if(request()->interval_type=='monthly'){
                $rules_array['on_or_on_the_m']='required';
                if(request()->on_or_on_the_m=='on'){
                    $rules_array['day_number_m']='required|numeric|min:1';
                }elseif (request()->on_or_on_the_m=='on_the') {
                    $rules_array['ordinal_m']='required';
                    $rules_array['week_day_name_m']='required';
                }
            }
            if(request()->interval_type=='yearly'){
                $rules_array['on_or_on_the_y']='required';

                if(request()->on_or_on_the_y=='on'){
                    $rules_array['day_number_y']='required|numeric|min:1';
                    $rules_array['month_name_y1']='required';
                }elseif (request()->on_or_on_the_y=='on_the') {
                    $rules_array['ordinal_y']='required';
                    $rules_array['week_day_name_y']='required';
                    $rules_array['month_name_y2']='required';
                }
            }

        }
        return $rules_array;
    }
    public function messages(){
        return [
            'service.required' => 'Select service',
            'service_type.required' => 'Select service type',
            'reccure_every.required'=>'This field is required',
            'on_or_on_the_m.required'=>'This field is required',
            'day_number_m.required'=>'This field is required',
            'ordinal_m.required'=>'This field is required',
            'week_day_name_m.required'=>'This field is required',

            'on_or_on_the_y.required'=>'This field is required',
            'day_number_y.required'=>'This field is required',
            'month_name_y1.required'=>'This field is required',

            'ordinal_y.required'=>'This field is required',
            'week_day_name_y.required'=>'This field is required',
            'month_name_y2.required'=>'This field is required',
            'end_by_or_after.required'=>'This field is required'
        ];
    }
}
