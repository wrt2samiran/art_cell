<?php
return [
	'input_labels'=>[
		'first_name'=>'الاسم الاول',
		'last_name'=>'الكنية',
		'email'=>'البريد الإلكتروني اختياري)',
		'contact_number'=>'رقم الاتصال',
		'state'=>'حالة',
		'city'=>'مدينة',
		'landmark_or_location'=>'المعلم / الموقع',
		'contact_duration'=>'مدة العقد',
		'property_type'=>'نوع الملكية',
		'service_required'=>'الخدمات المطلوبة',
		'images'=>'الصور (اختياري)',
		'no_of_resources'=>'عدد الموارد (اختياري)',
		'description'=>'وصف'
	],
	'input_placeholders'=>[
		'first_name'=>'أدخل الاسم الأول',
		'last_name'=>'إدخال اسم آخر',
		'email'=>'أدخل البريد الإلكتروني (اختياري)',
		'contact_number'=>'أدخل رقم الاتصال',
		'state'=>'اختر ولايه',
		'city'=>'اختر مدينة',
		'landmark_or_location'=>'المعلم / الموقع',
		'contact_duration'=>'مدة العقد',
		'property_type'=>'حدد نوع الخاصية',
		'images'=>'تحميل الصور (اختياري)',
		'no_of_resources'=>'عدد الموارد (اختياري)',
		'description'=>'وصف',
		'service'=>'حدد الخدمة',
		'work_details'=>'تفاصيل العمل باختصار'
	],
	'general_sentences'=>[
		'submit_quotation'=>'إرسال الاقتباس الخاص بك',
		'please_fillup'=>'يرجى ملء الاستمارة وإرسالها.',
		'submit'=>'إرسال',
		'add_service'=>'أضف خدمة',
        'disclaimer'=>'قد يختلف المبلغ الإجمالي حسب موقعك وزيارة الموقع',
        'total_amount'=>'المبلغ الإجمالي',
        'image_help'=>'تحميل ماكس. 3 صور من نوع jpeg / png / gif'
	],
	'validation_messages'=>[
            'first_name'=> [
                'required'=>'الإسم الأول مطلوب',
                'minlength'=> 'يجب أن يتكون الاسم الأول من حرفين',
                'maxlength'=> 'يجب ألا يزيد الاسم الأول عن 100 حرف'
            ],
            'last_name'=> [
                'required'=>  'إسم العائلة مطلوب',
                'minlength'=> 'يجب أن يتكون اسم العائلة من حرفين',
                'maxlength'=> 'يجب ألا يزيد الاسم الأخير عن 100 حرف'
            ],
            'email'=> [
                'required'=> 'البريد الالكتروني مطلوب',
                'maxlength'=> 'يجب ألا يزيد البريد الإلكتروني عن 150 حرفًا'
            ],
            'contact_number'=> [
                'required'=> 'رقم الهاتف مطلوب',
                'number'=>'أدخل رقمًا صالحًا'
            ],
            'state'=> [
                'required'=> 'الدولة مطلوبة',
            ],
            'city'=> [
                'required'=> 'المدينة مطلوبة',
            ],
            'landmark'=> [
                'required'=> 'المَعلم مطلوب',
                'accurate_address'=>'قم بسحب وإسقاط العلامة للحصول على عنوان دقيق.'
            ],
            'contract_duratio'=> [
                'required'=> 'مدة العقد مطلوبة',
            ],
            'property_type'=>[
               'required'=> 'حدد نوع الملكية'
            ],
            'work_details'=>[
                'required'=> 'أدخل تفاصيل العمل باختصار',
                'maxlength'=> 'الحد الأقصى المسموح به 250 حرفًا',
            ],
            'service'=>[
                'required'=> 'اختر الخدمة',
            ]
	]
];