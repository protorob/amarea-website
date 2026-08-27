<?php

use Kirby\Http\Response;
use Kirby\Toolkit\V;

require_once __DIR__ . '/env.php';
require_once __DIR__ . '/helpers.php';

return [
    'debug' => getenv('KIRBY_DEBUG') === 'true',

    // SMTP credentials come from the environment — set via a gitignored
    // .env file (see .env.example) both locally and on the deploy target,
    // never committed.
    'email' => [
        'transport' => [
            'type'     => 'smtp',
            'host'     => getenv('SMTP_HOST'),
            'port'     => (int) (getenv('SMTP_PORT') ?: 587),
            'security' => 'tls',
            'auth'     => true,
            'username' => getenv('SMTP_USERNAME'),
            'password' => getenv('SMTP_PASSWORD'),
        ],
        'from'     => getenv('SMTP_FROM') ?: 'no-reply@amarea.co',
        'fromName' => "A'Marea",
    ],

    'panel' => [
        'language' => 'en',
    ],

    // Single endpoint for the lead-capture form — used both by the
    // site-wide "Start Booking" modal and the embedded Contact form.
    // See docs/v1-build-plan.md §4.
    'routes' => [
        [
            'pattern' => 'lead',
            'method'  => 'POST',
            'action'  => function () {
                $kirby = kirby();
                $data  = $kirby->request()->data();

                // honeypot: real visitors never see or fill this field
                if (($data['website'] ?? '') !== '') {
                    return Response::json(['ok' => true]);
                }

                $required = [
                    'firstName', 'lastName', 'email', 'phone',
                    'country', 'billingType', 'about', 'referral',
                ];

                foreach ($required as $field) {
                    if (empty($data[$field])) {
                        return Response::json([
                            'ok'    => false,
                            'error' => 'missing_field',
                            'field' => $field,
                        ], 422);
                    }
                }

                if (V::email($data['email']) === false) {
                    return Response::json(['ok' => false, 'error' => 'invalid_email'], 422);
                }

                if ($data['billingType'] === 'professional' && empty($data['vatId'])) {
                    return Response::json([
                        'ok'    => false,
                        'error' => 'missing_field',
                        'field' => 'vatId',
                    ], 422);
                }

                $labels = [
                    'firstName'   => 'First name',
                    'lastName'    => 'Last name',
                    'email'       => 'Email',
                    'phone'       => 'Phone',
                    'country'     => 'Country of residence',
                    'billingType' => 'Billing type',
                    'vatId'       => 'VAT ID',
                    'linkedin'    => 'LinkedIn profile',
                    'about'       => 'About',
                    'referral'    => 'How did you hear from us',
                ];

                $body = "New waitlist signup from " . site()->title() . "\n\n";
                foreach ($labels as $key => $label) {
                    if (empty($data[$key])) {
                        continue;
                    }
                    $body .= $label . ': ' . $data[$key] . "\n";
                }

                $to = site()->email()->or('hello@amarea.co')->value();

                try {
                    $kirby->email([
                        'to'      => $to,
                        'replyTo' => $data['email'],
                        'subject' => 'New waitlist signup — ' . $data['firstName'] . ' ' . $data['lastName'],
                        'body'    => $body,
                    ]);
                } catch (\Throwable $e) {
                    return Response::json(['ok' => false, 'error' => 'send_failed'], 500);
                }

                return Response::json(['ok' => true]);
            },
        ],
    ],
];
