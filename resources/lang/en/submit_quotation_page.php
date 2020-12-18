<?php
return [
	'input_labels'=>[
		'first_name'=>'First Name',
		'last_name'=>'Last Name',
		'email'=>'Email (optional)',
		'contact_number'=>'Contact Number',
		'state'=>'State',
		'city'=>'City',
		'landmark_or_location'=>'Landmark/Location',
		'contact_duration'=>'Contract Duration',
		'property_type'=>'Property Type',
		'service_required'=>'Services Required',
		'images'=>'Images (Optional)',
		'no_of_resources'=>'No. Of Resources (optional)',
		'description'=>'Description'
	],
	'input_placeholders'=>[
		'first_name'=>'Enter First Name',
		'last_name'=>'Enter Last Name',
		'email'=>'Enter Email (optional)',
		'contact_number'=>'Enter Contact Number',
		'state'=>'Select State',
		'city'=>'Select City',
		'landmark_or_location'=>'Landmark/Location',
		'contact_duration'=>'Contract Duration',
		'property_type'=>'Select Property Type',
		'images'=>'Upload Images (Optional)',
		'no_of_resources'=>'No. Of Resources (optional)',
		'description'=>'Description',
		'service'=>'Select Service',
		'work_details'=>'Work details in short'
	],
	'general_sentences'=>[
		'submit_quotation'=>'Submit Your Quotation',
		'please_fillup'=>'Please fill up the form and submit.',
		'submit'=>'Submit',
		'add_service'=>'Add Service',
        'disclaimer'=>'The total amount may vary as per your property and site visit',
        'total_amount'=>'Total Amount',
        'image_help'=>'upload max. 3 images of type jpeg/png/gif'
	],
	'validation_messages'=>[
            'first_name'=> [
                'required'=>'First name is required',
                'minlength'=> 'First name should have 2 characters',
                'maxlength'=> 'First name should not be more then 100 characters'
            ],
            'last_name'=> [
                'required'=>  'Last name is required',
                'minlength'=> 'Last name should have 2 characters',
                'maxlength'=> 'Last name should not be more then 100 characters'
            ],
            'email'=> [
                'required'=> 'Email is required',
                'maxlength'=> 'Email should not be more than 150 characters'
            ],
            'contact_number'=> [
                'required'=> 'Phone number is required',
                'number'=>'Enter valid number'
            ],
            'state'=> [
                'required'=> 'State is required',
            ],
            'city'=> [
                'required'=> 'City is required',
            ],
            'landmark'=> [
                'required'=> 'Landmark is required',
                'accurate_address'=>'Drag and drop the marker for an accurate address.'
            ],
            'contract_duratio'=> [
                'required'=> 'Contract duration is required',
            ],
            'property_type'=>[
               'required'=> 'Select property type',
            ],
            'work_details'=>[
                'required'=> 'Enter work details in short',
                'maxlength'=> 'Maximum 250 characters allowed',
            ],
            'service'=>[
                'required'=> 'Select service',
            ]
	]
];