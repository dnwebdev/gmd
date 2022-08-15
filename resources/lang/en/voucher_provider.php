<?php

return [
    // Menu Voucher Page
    'menu' => [
        'voucher' => 'Promo Code',
        'list_of_voucher' => 'List of promo code',
        'new_voucher' => 'New Promo Code',
        'voucher_code' => 'Promo Code',
        'amount' => 'Discount Amount',
        'minimum_transaction' => 'Minimum Transaction',
        'description' => 'Description',
        'status' => 'Status',
        'no_voucher_yet' => '-- No Promo Code Yet --',
        'voucher_used' => 'Promo Code Used',
        'create_voucher' => 'Create New Promo Code',
        'edit_voucher' => 'Edit Promo Code'
    ],
    // Create Voucher (belum di translate)
    'create' => [
        'create_new_voucher' => 'Create New Promo Code',
        'voucher_inforation' => 'Promo Information',
        'voucher_code' => 'Promo Code',
        'voucher_currency' => 'Currency',
        'voucher_currency_empty' => 'Select Currency',
        'voucher_ammount_type' => 'Discount Type',
        'voucher_ammount_type_empty' => 'Select Amount Type',
        'voucher_ammount' => 'Discount Amount',
        'minimum_transaction_ammount' => 'Minimum Transaction',
        'max_use' => 'Max Use',
        'voucher_status' => 'Status',
        'voucher_description' => 'Description',
        'voucher_validity' => 'Promo Code Validity',
        'valid_start_date' => 'Start Date',
        'valid_end_date' => 'End Date',
        'schedule_start_date' => 'Schedule Start Date',
        'schedule_end_date' => 'Schedule End Date',
        'submit' => 'Submit',
        'active' => 'Active',
        'not_active' => 'Not Active',
        'fix_amount' => 'Fix Amount',
        'percentage' => 'Percentage',
        'min_people' => 'Min. Participant/Unit per Booking',
        'max_people' => 'Max. Participant/Unit per Booking',
        'product'    => 'This Promo Applied to Products:'
    ],
    // proses submit
    'process' => [
        'store_submit' => 'New Voucher Created',
        'update_submit' => 'Voucher Saved',
        'success' => 'Success',
        'oops' => 'Oops...',
    ],
    // validasi error
    'validate' => [
        'voucher_code' => 'Promo Code is required',
        'voucher_code_unique' => 'Promo codes must be unique and never been used.',
        'voucher_description' => 'Description is required',
        'minimum_amount' => 'Minimum Amount is required (Min:0)',
        'voucher_amount' => 'Invalid Promo Code Amount Format',
        'voucher_amount_min' => 'The Promo Code amount must be at least 1.',
    ],
];
