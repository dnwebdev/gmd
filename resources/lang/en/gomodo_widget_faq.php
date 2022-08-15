<?php 

return [
    'caption' => 'Gomodo Widget',
    'content' => [
        [
            'caption' => 'What is widget?',
            'description' => 'Widget is an application that can be placed on the web by inserting some codes into the web. Widgets can be images, flash, videos, social media links, booking buttons, etc. Each website has its own widget which is usually called default widgets. There are also paid or free widgets from widget providers.'
        ],
        [
            'caption' => 'Does Gomodo have a widget?',
            'description' => 'Yes, Gomodo provides a kind of widget that is easy to be placed on your own website. This booking-button-providing widget is free.'
        ],
        [
            'caption' => 'What is Gomodo Widget Function?',
            'description' => 'To create instant booking button on your website, as a result the customers would order your product easily and they do not need to go to your mygomodo.com site. They just need to click the Gomodo Widget button that has been installed on your website.'
        ],
        [
            'caption' => 'How to activate Your Gomodo Widget?',
            'setting-step' => [
                [
                    'title' => '<b>First:</b> please open product menu and choose product list as shown in the picture below.',
                    'desc' => '<img class="img-fluid" src="/landing/img/widget-step/en-widget-step-1.png" alt="widget-step-1" />'
                ],
                [
                    'title' => '<b>Second:</b> Choose the cross symbol of the desired product to create  a widget.',
                    'desc' => '<img class="img-fluid" src="/landing/img/widget-step/en-widget-step-2.png" alt="widget-step-2" />'
                ],
                [
                    'title' => '<b>Step Three:</b> Choose the “Create Widget” button as seen in the picture below.',
                    'desc' => '<img class="img-fluid" src="/landing/img/widget-step/en-widget-step-3.png" alt="widget-step-3" /> <p>Then, the “Product Widget Code” you need to create the widget will appear:</p> <img class="img-fluid" src="/landing/img/widget-step/en-widget-step-3a.png" alt="widget-step-3" />'
                ],
                [
                    'title' => '<b>Step Four:</b> click “copy” to copy the code provided on Gomodo product menu that will be pasted on your website (outside of Gomodo)',
                ],
                [
                    'title' => '<b>Step Five:</b> after that, login to your own site (wordpress) and drag your mouse to the Appearance menu → widget.',
                    'desc' => '<img class="img-fluid" src="/landing/img/widget-step/en-widget-step-5a.png" alt="widget-step-5" /> <p>On widget submenu, please drag and drop custom HTML block to the sidebar as seen in the picture below:</p> <img class="img-fluid" src="/landing/img/widget-step/en-widget-step-5b.png" alt="widget-step-5" /><img class="img-fluid" src="/landing/img/widget-step/en-widget-step-5c.png" alt="widget-step-5" /><p>Now, you can create your widget title and paste the previous code in the content and click done.</p><img class="img-fluid" src="/landing/img/widget-step/en-widget-step-5d.png" alt="widget-step-5" /><p>then, click save.</p><p>Here is the result example after doing all the above steps.</p><img class="img-fluid" src="/landing/img/widget-step/en-widget-step-5e.png" alt="widget-step-5" /><p>If you click the “Book Now” button, there will be details such as: order price, price total, departure date, and etc. It will be shown in the picture below as well:</p><img class="img-fluid" src="/landing/img/widget-step/en-widget-step-5f.png" alt="widget-step-5" /><p>Your customer will be able to proceed with an order by clicking this order button.</p>'
                ],
            ]
        ],
        [
            'caption' => 'How to customize your Gomodo widget?',
            'description' => 'Gomodo widget has a default display: blue box with "Book with Gomodo" written in white.</p> <p>Codes that need to be added to change your widget appearance:',
            'data-list' => [
                'data-background="(fill in the background you want,i.e black)"',
                'data-title="(fill in the title you want, i.e order now)"',
                'data-color="(fill in the color you want, i.e green)"',
                'data-align="(left, right, center, to adjust the widget to suit your desired position. The default is center)"'
            ],
            'code-set' => [
                [
                    'prefix' => 'The full example would be as follows:',
                    'code' => 'data-background="green" data-title=”book now” data-color=”white” data-align=”right”'
                ],
                [
                    'prefix' => 'after paste your customized code into the embed code:',
                    'code' => '<div><a class="gomodoEmbed" data-url=http://pandatour.mygomodo.com/product/detail/SKU5257015458132139507 data-background="green" data-title="Pesan Sekarang" data-color="white" data-align="right"></a><script src="http://mygomodo.com/gomodo-widget.js"></script></div>'
                ],
            ]
        ],
    ]
];
