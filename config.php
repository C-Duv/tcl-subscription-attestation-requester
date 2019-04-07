<?php

const TCL_CERTIFICATEREQUEST_URL = 'https://tcl.fr/Mon-TCL/Service-Attestation-d-abonnement2';
const TCL_SUCCESSFUL_RESPONSE_STRING = '<p class="ALIGN-center">Votre demande d\'attestation a bien été prise en compte</p>';

const MINIMUM_SUBSCRIBER_AGE = 15;

const USER_AGENT_POOL = [
    'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:65.0) Gecko/20100101 Firefox/65.0',
    'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:65.0) Gecko/20100101 Firefox/65.0',
    'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.80 Safari/537.36',
    'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.80 Safari/537.36',
];

return [
    'subscriptions' => [
        'John Doe' => [
            'subscription_number' => '012345678912',
            'subscriber_birth_date' => '1970-01-01',
            'send_certificate_to_email' => 'john@example.com',
        ],
        'Jane Doe' => [
            'subscription_number' => '219876543210',
            'subscriber_birth_date' => '1970-01-01',
            'send_certificate_to_email' => 'jane@example.com',
        ],
    ],
];
