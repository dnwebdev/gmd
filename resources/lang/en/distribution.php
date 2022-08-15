<?php

return [
    'intro' => [
        'title' => 'Distribution System at Gomodo',
        'description' => 'Distribution System in Gomodo is one of the marketing aspects, where Gomodo will help providers to distribute products they owned to the sales channels that have worked with Gomodo both Global Distribution System (GDS), Online Travel Agents (OTA) ) and Marketplace. So that by only using the Gomodo distribution system, the products that the provider owned can be distributed to various other marketing channels.',
        'button_text' => 'Learn More'
    ],
    'form' => [
        'title' => 'Submit Distribution Request',
        'subtitle'=> 'Provider',
        'input' => [
            [
                'label' => 'Your Name or Company',
                'type' => 'text',
                'name' => 'name',
                'additional_class' => ''
            ],
            [
                'label' => 'Your Email',
                'type' => 'text',
                'name' => 'email',
                'additional_class' => ''
            ],
            [
                'label' => 'Phone Number',
                'type' => 'text',
                'name' => 'phone',
                'additional_class' => 'number'
            ],
            [
                'label' => 'Your Message',
                'type' => 'textarea',
                'name' => 'message',
                'additional_class' => ''
            ]
        ],
        'submit' => 'Send Request',
        'contact_us' => 'For further information, contact us at: ',
        'swall' => [
            'success' => 'Thank you, your distribution request was successfully sent. Our team will contact you shortly for the next step.',
            'ok' => 'Got it'
        ],
        'already_exists'=>'You have already made a request for distribution, please kindly wait for our team to contact you soon',
    ]
];