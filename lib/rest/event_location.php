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
    'path'   => '/v0.dev/event/location/',
    'auth'   => '\rex_yform_rest_auth_token::checkToken',
    'type'   => \event_location::class,
    'query'  => \event_location::query(),
    'get'    => [
        'fields' => [
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
            'rex_event_location' => [
                'name',
                'name',
                'street',
                'zip',
                'locality',
                'lat',
                'lng',
            ],
        ],
    ],
    'delete' => [
        'fields' => [
            'rex_event_location' => [
                'id',
            ],
        ],
    ],
]));