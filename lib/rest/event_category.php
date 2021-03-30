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
    'path'   => '/v0.dev/event/category/',
    'auth'   => '\rex_yform_rest_auth_token::checkToken',
    'type'   => \event_category::class,
    'query'  => \event_category::query(),
    'get'    => [
        'fields' => [
            'rex_event_category' => [
                'id',
                'name',
                'image',
            ],
        ],
    ],
    'post'   => [
        'fields' => [
            'rex_event_category' => [
                'name',
                'image',
            ],
        ],
    ],
    'delete' => [
        'fields' => [
            'rex_event_category' => [
                'id',
            ],
        ],
    ],
]));