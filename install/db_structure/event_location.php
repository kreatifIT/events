<?php

/**
 * @author Kreatif GmbH
 * @author a.platter@kreatif.it
 * Date: 29.03.21
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace events;

use yform\usability\Usability;


$prio  = 0;
$langs = array_values(\rex_clang::getAll());
$table = \rex::getTable(\event_location::TABLE);

Usability::ensureValueField($table, 'row_start', 'html', [
    'prio' => $prio++,
], [
    'label' => '',
    'html'  => '<div class="event-date row"><div class="col-md-6">',
]);

Usability::ensureValueField($table, 'tab_start', 'events_tab_start', [
    'prio' => $prio++,
], [
    'list_hidden' => 1,
    'search'      => 0,
    'label'       => '',
    'db_type'     => 'none',
]);

foreach ($langs as $index => $lang) {
    Usability::ensureValueField($table, "name_{$lang->getId()}", 'text', [
        'list_hidden' => !($index == 0),
        'search'      => $index == 0,
        'prio'        => $prio++,
        'notice'      => 'Name des Veranstaltungsorts, z.B. <code>Zahlemann und Söhne GmbH & Co. KG</code>',
    ], [
        'label'      => 'Name',
        'attributes' => json_encode(['required' => 'required']),
        'db_type'    => 'varchar(191)',
    ]);

    Usability::ensureValueField($table, "street_{$lang->getId()}", 'text', [
        'list_hidden' => 1,
        'search'      => 0,
        'prio'        => $prio++,
        'notice'      => 'Bitte Straßennamen und Hausnummer eingeben, z.B. <code>Alexanderplatz 1</code>',
    ], [
        'label'   => 'Straße',
        'db_type' => 'varchar(191)',
    ]);

    Usability::ensureValueField($table, "locality_{$lang->getId()}", 'text', [
        'list_hidden' => 1,
        'search'      => 0,
        'prio'        => $prio++,
        'notice'      => 'Bitte Namen der Stadt angeben, z.B <code>Berlin</code>',
    ], [
        'label'   => 'Ort / Stadt',
        'db_type' => 'varchar(191)',
    ]);

    if (($index + 1) < count($langs)) {
        Usability::ensureValueField($table, "tab_break_{$lang->getId()}", 'events_tab_break', [
            'prio' => $prio++,
        ], [
            'list_hidden' => 1,
            'search'      => 0,
            'label'       => '',
            'db_type'     => 'none',
        ]);
    }
}

Usability::ensureValueField($table, 'tab_end', 'events_tab_end', [
    'prio' => $prio++,
], [
    'list_hidden' => 1,
    'search'      => 0,
    'label'       => '',
    'db_type'     => 'none',
]);

Usability::ensureValueField($table, 'zip', 'text', [
    'list_hidden' => 1,
    'search'      => 0,
    'prio'        => $prio++,
    'notice'      => 'Bitte gültige Postleitzahl eingeben, z.B.  <code>10115</code>',
], [
    'label'   => 'PLZ',
    'db_type' => 'varchar(191)',
]);

Usability::ensureValueField($table, 'countrycode', 'choice', [
    'prio'        => $prio++,
    'list_hidden' => 1,
], [
    'search'   => 0,
    'multiple' => 0,
    'label'    => 'Staat',
    'default'  => 'DE',
    'choices'  => file_get_contents(\rex_addon::get('events')->getPath('install/assets/countries.json')),
    'db_type'  => 'varchar(191)',
]);

Usability::ensureUserFields($table, $prio++);
Usability::ensureDateFields($table, $prio++);

Usability::ensureValueField($table, 'col', 'html', [
    'prio' => $prio++,
], [
    'label' => '',
    'html'  => '</div><div class="col-md-6">',
]);

Usability::ensureValueField($table, 'lat_lng', 'osm_geocode', [
    'prio' => $prio++,
], [
    'list_hidden' => 1,
    'search'      => 0,
    'height'      => 500,
    'no_db'       => 1,
    'latlng'      => 'lat,lng',
    'label'       => 'Standort',
    'address'     => 'street_1,zip,locality_1',
]);

Usability::ensureValueField($table, 'col_row_start', 'html', [
    'prio' => $prio++,
], [
    'label' => '',
    'html'  => '<div class="event-layout row"><div class="col-md-6">',
]);

Usability::ensureValueField($table, 'lat', 'number', [
    'list_hidden' => 1,
    'search'      => 0,
    'prio'        => $prio++,
], [
    'label'     => 'Breitengrad (lat)',
    'scale'     => 8,
    'precision' => 10,
]);

Usability::ensureValueField($table, 'col_row_col', 'html', [
    'prio' => $prio++,
], [
    'label' => '',
    'html'  => '</div><div class="col-md-6">',
]);

Usability::ensureValueField($table, 'lng', 'number', [
    'list_hidden' => 1,
    'search'      => 0,
    'prio'        => $prio++,
], [
    'label'     => 'Längengrad (lng)',
    'scale'     => 8,
    'precision' => 10,
]);

Usability::ensureValueField($table, 'col_row_end', 'html', [
    'prio' => $prio++,
], [
    'label' => '',
    'html'  => '</div></div>',
]);

Usability::ensureValueField($table, 'raw_name', 'text', [
    'prio' => $prio++,
], [
    'list_hidden' => 1,
    'search'      => 0,
    'label'       => 'Name von Import',
]);

Usability::ensureValueField($table, 'raw_result', 'data_dump', [
    'list_hidden' => 1,
    'search'      => 0,
    'prio'        => $prio++,
], [
    'label' => 'Rohdaten von .ics Import',
]);

Usability::ensureValueField($table, 'row_end', 'html', [
    'prio' => $prio++,
], [
    'label' => '',
    'html'  => '</div></div>',
]);

$yTable = \rex_yform_manager_table::get($table);
\rex_yform_manager_table_api::generateTableAndFields($yTable);
