<?php

return [
    'alert' => 'This form is required if you use a credit card payment method.',

    // KYC Gamification
    'kyc-gamification' => [
        'title' => 'Verify Your Business Profile',
        'desc' => 'Verify your business now to access all of Gomodo\'s features and services',
        'button' => 'Verify your Profile Now',
    ],

    // Front Page
    'front-page' => [
        'title' => 'Verify Your Business',
        'desc' => 'Get access to various benefits and Gomodo services:',
        'pre_list' => 'This is the benefits if you complete your account:',
        'list' => [
            [
                'caption' => 'Accept payments by Credit Card',
                'desc' => 'Accept payment of orders with VISA and Mastercard credit cards.',
            ],
            [
                'caption' => 'Access to Insurance services',
                'desc' => 'Additional insurance protections are available for your service and travel products.',
            ],
            [
                'caption' => 'Supports P2P (Peer to Peer) Lending for your business financing',
                'desc' => 'Submit a request for business financing for your financial needs through our official partners.',
            ],
        ],
        'complete_now' => 'COMPLETE MY ACCOUNT',
    ],

    // Success
    'submit_success' => [
        'title' => 'Documents have been successfully uploaded',
        'button' => 'Back to Home',
    ],

    // Intro
    'intro' => 'Before verifying your business, you need to prepare these documents as follows:',

    // Form Usaha Berstatus PT/CV
    'caption1' => 'Verification of Business Entity',
    'company_name' => 'Company Name',
    'company_type' => 'Company Type',
    'company_npwp' => 'Photo or scan of Company\'s Taxpayer Identification Number',
    'company_act' => 'Photo or scan of Deed of Establishment',
    'company_certificate' => 'Photo or scan of Company Registration Certificate',
    'company_domicile' => 'Photo or scan of Domicile Letter',
    'business_license' => 'Photo or scan of Trading Business License',
    'identity_card' => 'Photo or scan of Director\'s ID Card',
    'tax_number' => 'Photo or scan of the Director\'s Taxpayer Identification Number',

    // Form Usaha Berstatus Non-PT/CV
    'caption2' => 'Verification of Individual Businesses',
    'b_identity_card' => 'Photo or scan of the Owner\'s ID Card',
    'family_card' => 'Photo or scan of Family Card',
    'tax_id' => 'Photo or scan of Owner\'s Taxpayer Identification Number',
    'police_certificate' => 'Photo or scan of Police Certificates of Good Conduct (SKCK)',
    'bank_statement' => 'Photo or scan of bank statement',
    'owner_photo' => 'Photo or scan of owner\'s photo (formal photo or selfie)',
    'phone_number' => 'Mobile Phone Number',
    'office_address' => 'Company Address',

    // Dropify
    'default1' => 'Drag and drop a file here or click to choose file',
    'default2' => 'Use PDF, JPG, JPEG or max PNG files. 2MB.',
    'error' => 'Ooops, something wrong appended',
    'status'=>[
        'need_approval'=>'Need Approval',
        'approved'=>'Approved',
        'rejected'=>'Rejected',
    ],
    'response'=>[
        'ok'=>'Your Request has been submitted, Please wait for Approval',
        'nothing_change'=>'Your Request has been submitted',
    ],


    // Button
    'verif'=> 'Verify Now'
];