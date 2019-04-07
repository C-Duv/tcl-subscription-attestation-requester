<?php

$config = require(__DIR__ . '/config.php');

function sendHttpRequest($parameters) {
    $curlHandler = curl_init();

    $request_headers = [
        'DNT: 1',
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9',
        'Accept-Language: fr-FR,fr;q=0.8,en-US;q=0.6,en;q=0.4',
        'Connection: keep-alive',
        'Pragma: no-cache',
        'Cache-Control: no-cache',
    ];

    curl_setopt_array(
        $curlHandler,
        [
            CURLOPT_URL => TCL_CERTIFICATEREQUEST_URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $parameters,
            CURLOPT_HTTPHEADER => $request_headers,
            CURLOPT_REFERER => TCL_CERTIFICATEREQUEST_URL,
            CURLOPT_USERAGENT => USER_AGENT_POOL[array_rand(USER_AGENT_POOL, 1)],
        ]
    );

    $response = curl_exec($curlHandler);
    $curlError = curl_error($curlHandler);
    if ($curlError) {
        trigger_error(curl_error($curlHandler));
    }

    curl_close($curlHandler);
    return $response;
}

function createRequestParameters(
    $subscription_number,
    $email,
    DateTime $birth_date,
    DateTime $certificate_start_date,
    $certificate_end_date = null
) {
    if ($certificate_end_date === null) {
        $certificate_end_date = clone $certificate_start_date;
        $certificate_end_date->setDate(
            $certificate_start_date->format('Y'),
            $certificate_start_date->format('m'),
            $certificate_start_date->format('t')
        );
    }

    var_dump($birth_date->format('c'));
    var_dump($certificate_start_date->format('c'));
    var_dump($certificate_end_date->format('c'));

    $bodyValues = [
        'classe_id' => 132, // From TCL_CERTIFICATEREQUEST_URL
        'titre' => 'Demande_attestationanonymous' . date('dmYHi'),
        'envoyer' => 'Envoyer',

        'type_carte' => 0, // 0 = "Carte Técély"
        'num_tecely' => $subscription_number,
        'email' => $email,
        'jour_date_naissance' => $birth_date->format('d'),
        'mois_date_naissance' => $birth_date->format('m'),
        'annee_date_naissance' => $birth_date->format('Y'),

        'jour_date_debut' => $certificate_start_date->format('d'),
        'mois_date_debut' => $certificate_start_date->format('m'),
        'annee_date_debut' => $certificate_start_date->format('Y'),
        'jour_date_fin' => $certificate_end_date->format('d'),
        'mois_date_fin' => $certificate_end_date->format('m'),
        'annee_date_fin' => $certificate_end_date->format('Y'),
    ];
    return $bodyValues;
}

function askCertificate($subscription_name, $subscription_infos, $certificate_startdate, $certificate_enddate) {
    $subscriber_birth_date = DateTime::createFromFormat('Y-m-d', $subscription_infos['subscriber_birth_date']);
    if ($subscriber_birth_date === false) {
        $subscriber_birth_date = new DateTime();
        $subscriber_birth_date->setDate(
            rand(1970, date('Y') - MINIMUM_SUBSCRIBER_AGE),
            rand(1, 12),
            rand(1, 31)
        );
    }
    $request_parameters = createRequestParameters(
        $subscription_infos['subscription_number'],
        $subscription_infos['send_certificate_to_email'],
        $subscriber_birth_date,
        $certificate_startdate,
        $certificate_enddate
    );
    $response_body = sendHttpRequest($request_parameters);

    if (strpos($response_body, TCL_SUCCESSFUL_RESPONSE_STRING)) {
        echo 'Demande "' . $subscription_name . '": Demande d\'attestation acceptée par le serveur.' . PHP_EOL;
    } else {
        echo 'Demande "' . $subscription_name . '": Le serveur n\'a pas accepté la demande d\'attestation.' . PHP_EOL;
    }
}

if (true) { //TODO: Gérer une date (ou un intervalle de date) d'attestation
    // Request for current month (from 1st day to last day):

    $requested_start_date = new DateTime('now');
    $requested_start_date->setDate(
        $requested_start_date->format('Y'),
        $requested_start_date->format('m'),
        1
    );
    $requested_end_date = null;
}

/** Loop through each configured subscriptions **/
foreach ($config['subscriptions'] as $currentSubscriptionName => $currentSubscription) {
    askCertificate($currentSubscriptionName, $currentSubscription, $requested_start_date, $requested_end_date);
}
/** /Loop through each configured subscriptions **/
