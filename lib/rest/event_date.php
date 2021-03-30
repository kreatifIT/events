<?php

/**
 * @author Kreatif GmbH
 * @author a.platter@kreatif.it
 * Date: 29.03.21
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

\rex_yform_rest::addRoute(new \rex_yform_rest_route([
    'path'   => '/v0.dev/event/date/',
    'auth'   => '\rex_yform_rest_auth_token::checkToken',
    'type'   => \event_date::class,
    'query'  => \event_date::query(),
    'get'    => [
        'fields' => [
            'rex_event_date'     => [
                'id',
                'name',
                'description',
                'location',
                'image',
                'startDate',
                'doorTime',
                'endDate',
                'eventStatus',
                'offers_url',
                'offers_price',
                'offers_availability',
                'url',
            ],
            'rex_event_category' => [
                'id',
                'name',
                'image',
            ],
            'rex_event_location' => [
                'id',
                'name',
                'street',
                'zip',
                'locality',
                'lat',
                'lng',
            ],
        ],
    ],
    'post'   => [
        'fields' => [
            'rex_event_date' => [
                'name',
                'description',
                'location',
                'image',
                'startDate',
                'doorTime',
                'endDate',
                'eventStatus',
                'offers_url',
                'offers_price',
                'offers_availability',
                'url',
            ],
        ],
    ],
    'delete' => [
        'fields' => [
            'rex_event_date' => [
                'id',
            ],
        ],
    ],
]));