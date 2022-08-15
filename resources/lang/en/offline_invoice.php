<?php

return [
    'caption' => 'E-Invoice',
    'create_invoice' => 'Create E-Invoice',
    'progress-1' => 'Invoice Informations',
    'progress-2' => 'Customer’s Information',
    'progress-3' => 'Publish Invoice',
    'page-1' => [
        'caption' => 'E-Invoice Details',
        'caption-detail' => 'E-Invoice Details',
        'create' => 'Create Invoice',
        'product_name' => 'Product Name *',
        'deadline' => 'Invoice Due Date',
        'detail' => 'E-Invoice Details',
        'description' => 'Description *',
        'price' => 'Price per Unit *',
        'amount' => 'Quantity *',
        'discount' => 'Discount Name',
        'nominal_discount' => 'Discount Amount',
        'percentage' => 'Percentage',
        'fixed' => 'Fixed',
        'total' => 'Total',
        'discount_name'=>'Discount',
        'cc_fee'=>'Credit Card Fee',
        'grandtotal' => 'Grand Total',
        'next' => 'Next Step',
        'allow_credit_card'=>' Allow Credit Card Payment',
        'settlement'=>'Settlement take 5-7 days depends on Bank regulation'
    ],
    'page-2' => [
        'preview' => 'E-Invoice Preview',
        'caption' => 'Input order data',
        'info' => 'Customer Info',
        'full_name' => 'Full Name',
        'phone' => 'Phone Number',
        'email' => 'Email Address',
        'address' => 'Address',
        'cancel' => 'Cancel',
        'previous' => 'Previous Step',
        'send' => 'Save and Send Invoice',
        'country' => 'Country',
        'city' => 'City',
        'discount' => 'Discount',
    ],
    'page-3' => [
        'caption' => 'Invoice Issued',
        'caption_content' => 'E-Invoice Sent',
        'desc_content' => 'E-Invoice was successfully created and sent to customer\'s email address.',
        'desc_content2' => 'Check the details on List of E-Invoice.',
        'inv_list' => 'Invoice List'
    ],
    'error' => [
        'field' => 'This Field is Required',
        'value1' => 'This Field minimum value is 1',
        'save' => 'This Field is Required & must be save first',
        'must_completed' => 'Form must be completed'
    ],
    'resend'=>[
        'button_table'=>'Resend',
        'action'=>'Action',
        'confirmation'=>'Are You Sure Want to Resend this E-Invoice?',
        'customer'=>'Customer',
        'cannot_process'=>'We cannot processed your request'
    ],
    'cancel'=>[
        'modal_desc'=>'Cancel Order?',
        'yes'=> 'Yes, cancel',
        'no'=>'No'
    ],
    'validation'=>[
        'min_100'=>'Minimum Transaction is IDR 10,000.'
    ]
];