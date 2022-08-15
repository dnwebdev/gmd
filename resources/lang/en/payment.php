<?php
return [
    'success_cod' => [
        'payment_on_site' => 'Payment On Site',
        'title' => 'Thank you for your order',
        'description' => 'Please settle your payment with :company with cash on site',
        'sub_description' => 'If you have any question, please contact us at',
        'whatsapp' => 'Chat Us on Whatsapp',
        'email' => 'Email at :email',
        'back' => 'Back to Homepage'
    ],
    'validation-ovo' => [
        'check_phone' => 'Please check your OVO Application',
        'api_validation_error' => 'There is invalid input in one of the required request fields.',
        'user_did_not_authorize_the_payment' => 'User did not authorize the payment request within time limit.',
        'user_declined_the_transaction' => 'User declined the payment request.',
        'phone_number_not_registered' => 'Phone number the user tried to pay is not registered',
        'external_error' => 'There is an error on ewallet provider side. Please contact our support for further assistance.',
        'sending_transaction_error' => 'We cannot send the transaction. Please contact our support for further assistance.',
        'ewallet_app_unreachable' => 'The ewallet provider/server can not reach the user ewallet app/phone. Common cases are the ewallet app is uninstalled.',
        'duplicate_payment' => 'The payment with the same external_id has already been made before.',
        'ewallet_type_not_supported' => 'Your requested ewallet_type is not supported yet',
        'development_mode_payment_acknowledged' => 'Payment can only be used in live mode, if you want to perform testing procedure',
        'request_forbidden_error' => 'API key in use does not have necessary permissions to perform the request.',
        'ovo_timeout_error' => 'There was a connection timeout from the OVO app to the OVO server',
        'credentials_error' => 'The merchant is not registered in e-wallet provider system',
        'account_authentication_error' => 'User authentication has failed',
        'account_blocked_error' => 'Unable to process the transaction because the user account is blocked',
        'development_mode_payment_simulation_acknowledged' => 'Your payment has been acknowledged. Please use amount 80001 to simulate a successful payment',
        'duplicate_payment_request_error' => 'There is already a payment request'
    ],
    'validation_linkaja' => [
        'success_payment' => 'Response for a successfully paid e-wallet transaction',
        'failed_payment' => 'There is an error on when the payment processed'
    ]
];