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
$table = \rex::getTable(\event_category::TABLE);


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
    ], [
        'label'   => 'Titel',
        'db_type' => 'varchar(191)',
    ]);

    Usability::ensureValidateField($table, "name_{$lang->getId()}", 'empty', [
        'prio' => $prio++,
    ], [
        'list_hidden'  => 1,
        'search'       => 0,
        'message'      => 'Bitte geben Sie für alle Sprachen einen Namen ein',
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

Usability::ensureValueField($table, 'image', 'event_media', [
    'prio' => $prio++,
], [
    'list_hidden' => 1,
    'search'      => 0,
    'label'       => 'Bild',
    'db_type'     => 'varchar(191)',
]);

Usability::ensureStatusField($table, $prio++);
Usability::ensurePriorityField($table, $prio++);
Usability::ensureUserFields($table, $prio++);
Usability::ensureDateFields($table, $prio++);

$yTable = \rex_yform_manager_table::get($table);
\rex_yform_manager_table_api::generateTableAndFields($yTable);
