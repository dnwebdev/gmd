<?php

return [
    'title_header' => 'Build and grow your business even further',
    'content_header' => 'Every business has the opportunity to develop more successful. At Gomodo, we have a loan feature that can be utilized to grow your business. Apply for a loan now and you will be able to use it immediately after the loan is confirmed.',
    'previous' => 'Back',
    'next' => 'Next',
    'submit' => 'Submit',
    'finish' => 'Finish',
    'validation' => [
        'not_upload_yet' => 'No File',
        'ktp' => 'The ktp field is required',
        'npwp' => 'The npwp field is required',
        'siup' => 'The siup field is required',
        'min_amount' => 'The minimum loan must be at least IDR ',
        'max_amount' => 'Max amount for loan is IDR ',
    ],
    'not_kyc' => [
        'title' => 'Verify your business to use this feature now!',
        'description' => 'The Financing feature can be fully accessed after your business has been successfully verified. Verify your business now to be able to apply for business financing through our official partners.',
        'button' => 'Verify My Business'
    ],
    'step-1' => [
        'description' => 'Complete the following information to apply for a loan!',
        'request_amount' => 'Your requested amount (min. IDR 10.000.000)',
        'loan_term' => 'Loan term'
    ],
    'step-2' => [
        'title' => 'Loan Funds',
        'description' => 'Upload the following loan terms documents:',
        'documents' => [
            'corporate_check' => 'Use your current ID, Taxpayer Registration Number and Trade Business License',
            'personal_check' => 'Use your current ID and Taxpayer Registration Number',
            'corporate' => [
                [
                    'title' => 'Loanee ID',
                    'name' => 'ktp'
                ],
                [
                    'title' => 'Taxpayer Registration Number (NPWP)',
                    'name' => 'npwp'
                ],
                [
                    'title' => 'Trade Business License (SIUP)',
                    'name' => 'siup'
                ],
                [
                    'title' => 'Deed of Establishment',
                    'name' => 'founding_deed'
                ],
                [
                    'title' => 'Deed of Modification (if available)',
                    'name' => 'change_certificate'
                ],
                [
                    'title' => 'Ministerial Decree',
                    'name' => 'sk_menteri'
                ],
                [
                    'title' => 'Company Registration Certificate',
                    'name' => 'company_signatures'
                ],
                [
                    'title' => 'Financial Report (includes report from the last 2 months)',
                    'name' => 'report_statement'
                ],
                [
                    'title' => 'Bank Account Book (includes mutation from the last 3 months)',
                    'name' => 'document_bank'
                ],
            ],
            'personal' => [
                [
                    'title' => 'Loanee ID',
                    'name' => 'ktp'
                ],
                [
                    'title' => 'Taxpayer Registration Number (NPWP)',
                    'name' => 'npwp'
                ],
                [
                    'title' => 'Spouse ID (if married)',
                    'name' => 'ktp_couples'
                ],
                [
                    'title' => 'Family Card',
                    'name' => 'family_card'
                ],
                [
                    'title' => 'Bank Account Book (includes mutation from the last 3 months)',
                    'name' => 'document_bank'
                ],
            ],
        ]
    ],
    'step-3' => [
        'tnc_table' => [
            [
                'text' => 'Your requested amount',
                'id' => 'request_amount'
            ],
            [
                'text' => 'Loan Term',
                'id' => 'loan_term'
            ],
            [
                'text' => 'Interest Rate',
                'id' => 'interest_rate',
                'value' => '1,5% - 2% per month'
            ],
            [
                'text' => 'Provision Fee',
                'id' => 'provision_fee'
            ],
            [
                'text' => 'Late Fee',
                'id' => 'late_fee'
            ],
            [
                'text' => 'Fastest Settlement Fee',
                'id' => 'fastest_settlement_fee'
            ],
            [
                'text' => 'Administration Fee',
                'id' => 'administration_fee'
            ],
            [
                'text' => 'Insurance',
                'id' => 'insurance'
            ],
        ],
        'tnc_text' => 'Saya telah membaca dan menyetujui <a href="" class="text-primary-300" style="text-decoration: none" data-toggle="modal" data-target="#modal_default">Term and Condition</a> untuk proses peminjaman dana'
    ],
    'success' => [
        'title' => 'You have successfully requested loan funds',
        'description' => 'You have finished the loan funds request process and our team will soon process your request for it to be confirmed quickly. If your request is being agreed then your funds will automatically be sent to your balance.'
    ],
];