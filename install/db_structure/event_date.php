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
$table = \rex::getTable(\event_date::TABLE);


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
        'notice'      => 'Titel der Veranstaltung',
    ], [
        'label'   => 'Titel',
        'db_type' => 'varchar(191)',
    ]);

    Usability::ensureValueField($table, "teaser_{$lang->getId()}", 'textarea', [
        'list_hidden' => 1,
        'search'      => 0,
        'prio'        => $prio++,
        'notice'      => 'Ein kurzer Anreißer-Text oder eine Zusammenfassung, die in der Terminübersicht ausgegeben werden kann.',
    ], [
        'label' => 'Teaser',
    ]);

    Usability::ensureValueField($table, "description_{$lang->getId()}", 'textarea', [
        'list_hidden' => 1,
        'search'      => 0,
        'prio'        => $prio++,
        'notice'      => 'Geben Sie zusätzliche Informationen zur Veranstaltung an.',
    ], [
        'label' => 'Beschreibung',
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


Usability::ensureValueField($table, 'raw', 'data_dump', [
    'prio' => $prio++,
], [
    'list_hidden' => 1,
    'search'      => 0,
    'label'       => 'Rohdaten von Import',
]);


Usability::ensureValueField($table, 'col', 'html', [
    'prio' => $prio++,
], [
    'label' => '',
    'html'  => '</div><div class="col-md-3">',
]);

Usability::ensureValueField($table, 'start', 'datetime', [
    'prio'        => $prio++,
    'format'      => 'DD.MM.YYYY HH:ii',
    'widget'      => 'input:text',
    'list_hidden' => 0,
    'notice'      => 'Geben Sie den Beginn der Veranstaltung ein – falls es eine separate Einlass-Zeit gibt, können Sie diese nachfolgend abweichend eintragen.',
], [
    'search'     => 0,
    'label'      => 'Beginn',
    'attributes' => json_encode(['data-yform-tools-datetimepicker' => 'DD.MM.YYYY HH:ii', 'autocomplete' => 'off']),
    'db_type'    => 'datetime',
]);

Usability::ensureValueField($table, 'end', 'datetime', [
    'prio'        => $prio++,
    'format'      => 'DD.MM.YYYY HH:ii',
    'widget'      => 'input:text',
    'list_hidden' => 1,
    'notice'      => 'Geben Sie das voraussichtliche Ende der Veranstaltung an. (optional)',
], [
    'search'     => 0,
    'label'      => 'Ende',
    'attributes' => json_encode(['data-yform-tools-datetimepicker' => 'DD.MM.YYYY HH:ii', 'autocomplete' => 'off']),
    'db_type'    => 'datetime',
]);

Usability::ensureValueField($table, 'doorTime', 'time', [
    'prio'        => $prio++,
    'format'      => 'H:i',
    'widget'      => 'input:text',
    'list_hidden' => 1,
], [
    'search'     => 0,
    'label'      => 'Einlass',
    'attributes' => json_encode(['type' => 'time']),
    'db_type'    => 'time',
]);

Usability::ensureValueField($table, 'all_day', 'choice', [
    'prio'        => $prio++,
    'list_hidden' => 0,
    'notice'      => 'Wenn "ja", dann werden die angegeben Uhrzeiten ignoriert.',
], [
    'search'  => 0,
    'label'   => 'Ganztägig',
    'choices' => json_encode(['translate:no' => 0, 'translate:yes' => 1]),
    'db_type' => 'tinyint(1)',
]);

Usability::ensureValueField($table, 'location', 'be_manager_relation', [
    'prio'         => $prio++,
    'list_hidden'  => 0,
    'empty_option' => 1,
    'notice'       => 'Wählen Sie den passenden Veranstaltungsort aus',
], [
    'search'  => 0,
    'label'   => 'Veranstaltungsort',
    'type'    => 2,
    'table'   => \rex::getTable(\event_location::TABLE),
    'field'   => 'name_1',
    'db_type' => 'int',
]);

Usability::ensureValueField($table, 'categories', 'be_manager_relation', [
    'prio'         => $prio++,
    'list_hidden'  => 0,
    'empty_option' => 1,
], [
    'search'  => 0,
    'label'   => 'Kategorien',
    'type'    => 3,
    'table'   => \rex::getTable(\event_category::TABLE),
    'field'   => 'name_1',
    'db_type' => 'int',
]);

Usability::ensureValueField($table, 'col2', 'html', [
    'prio' => $prio++,
], [
    'label' => '',
    'html'  => '</div><div class="col-md-3">',
]);

Usability::ensureValueField($table, 'image', 'event_media', [
    'prio' => $prio++,
], [
    'list_hidden' => 1,
    'search'      => 0,
    'label'       => 'Bild',
    'db_type'     => 'varchar(191)',
]);

Usability::ensureValueField($table, 'url', 'text', [
    'prio'   => $prio++,
    'notice' => 'Ist die Veranstaltung ein Hinweis auf eine Veranstaltung auf einer anderen Website oder mit mehr Informationen, dann geben Sie hier die Adresse zu einer externen Website ein, z.B. <code>http://www.meine-website.de/meine-veranstaltung/</code>',
], [
    'list_hidden' => 1,
    'search'      => 0,
    'label'       => 'externer Verweis',
    'db_type'     => 'varchar(191)',
]);

Usability::ensureValidateField($table, 'url', 'type', [
    'prio' => $prio++,
], [
    'list_hidden'  => 1,
    'search'       => 0,
    'not_required' => 1,
    'type'         => 'url',
    'message'      => 'Bitte eine valide URL eingeben',
]);

Usability::ensureValueField($table, 'source_url', 'text', [
    'prio' => $prio++,
], [
    'list_hidden' => 1,
    'search'      => 0,
    'label'       => 'Quelle',
    'db_type'     => 'varchar(191)',
    'attributes'  => json_encode(['readonly' => 'readonly']),
]);

Usability::ensureValueField($table, 'uid', 'emptyname', [
    'prio' => $prio++,
], [
    'list_hidden' => 1,
    'search'      => 0,
    'label'       => 'einmalige ID für ICS-Import',
    'db_type'     => 'varchar(191)',
    'attributes'  => json_encode(['readonly' => 'readonly']),
]);

Usability::ensureValidateField($table, 'uid', 'unique', [
    'prio' => $prio++,
], [
    'list_hidden'  => 1,
    'search'       => 0,
    'empty_option' => 1,
    'type'         => 'url',
    'message'      => 'Die UID ist nicht eindeutig',
]);

Usability::ensureValueField($table, 'eventStatus', 'choice', [
    'list_hidden' => 1,
    'search'      => 0,
    'label'       => 'translate:status',
    'prio'        => $prio++,
], [
    'db_type'  => 'int',
    'expanded' => 0,
    'multiple' => 0,
    'default'  => 1,
    'choices'  => json_encode([
        'findet statt' => 1,
        'ohne Status'  => 0,
        'abgesagt'     => -1,
    ]),
]);

Usability::ensureValueField($table, 'offer', 'be_manager_relation', [
    'prio'         => $prio++,
    'list_hidden'  => 1,
    'empty_option' => 1,
], [
    'search'         => 0,
    'label'          => 'Angebote',
    'type'           => 5,
    'table'          => \rex::getTable(\event_date_offer::TABLE),
    'relation_table' => \rex::getTable(\event_date_offer::TABLE),
    'field'          => 'date_id',
    'db_type'        => 'int',
]);

Usability::ensureUserFields($table, $prio++);
Usability::ensureDateFields($table, $prio++);

Usability::ensureValueField($table, 'row_end', 'html', [
    'prio' => $prio + 10,
], [
    'label' => '',
    'html'  => '</div></div>',
]);

$yTable = \rex_yform_manager_table::get($table);
\rex_yform_manager_table_api::generateTableAndFields($yTable);
