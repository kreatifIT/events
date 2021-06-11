<?php

use ICal\ICal;


class rex_cronjob_events_ics_import extends rex_cronjob
{
    public function execute()
    {
        $debugData = []; // Debug-Array, das bei enstprechender Option ausgegeben wird
        $savedIds  = [];

        // Das ICS-Objekt initialisieren und Datei abrufen
        try {
            $ical = new ICal($this->getParam('url'), [
                'defaultSpan'                 => 2,     // Default value
                'defaultTimeZone'             => 'UTC',
                'defaultWeekStart'            => 'MO',  // Default value
                'disableCharacterReplacement' => false, // Default value
                'skipRecurrence'              => false, // Default value
                'useTimeZoneWithRRules'       => false, // Default value
                'filterDaysAfter'             => 365, // Default value
                'filterDaysBefore'            => $this->getParam('beforetoday'), // Default value
            ]);

            $debugData['$ical'] = $ical;
        } catch (\Exception $e) {
            $this->setMessage('ICS-Datei Konnte nicht importiert werden. -> ' . $e->getMessage());
            return false;
        }

        if ($ical->eventCount) {
            $langCodes = [];
            $updatedCategories = [];
            foreach (rex_clang::getAll(true) as $lang) {
                $langCodes[$lang->getId()] = $lang->getCode();
            }

            // Wenn die Option "default" nicht gesetzt ist, werden zusätzliche Events-Kategorien angelegt:
//        if ($this->getParam('category_sync') !== 'default') {
//            // ...andernfalls werden Kategorien aus der ICS-Datei in Events angelegt
//            $sql = rex_sql::factory()->setDebug(0);
//
//            // Herausfinden, welche Kategorien in Events vorkommen
//            $existing_categories                = $sql->getArray('SELECT id, name_' . $this->getParam('clang_id') . ' AS name FROM `rex_event_category`');
//            $debugData['$existing_categories'] = $existing_categories;
//            $existing_categories_names          = [];
//            // TODO: Hier stattdessen array_column($sql->getArray(...), 'name', 'id') verwenden?
//            foreach ($existing_categories as $existing_category) {
//                $existing_categories_names[] = $existing_category['name'];
//            }
//
//            // Herausfinden, welche Kategorien in der ICS-Datei vorkommen
//            $category_names_per_event = [];
//            if (count($vEvents)) {
//                foreach ($vEvents as $vEvent) {
//                    $category_names_per_event = array_merge($category_names_per_event, explode(",", $vEvent['CATEGORIES']));
//                }
//            }
//            $category_names_per_event = array_unique($category_names_per_event);
//
//            // Herausfinden, welche Kategorie-Namen noch nicht vorhanden sind
//            $debugData['$add_categories'] = $add_categories = array_diff($category_names_per_event, $existing_categories_names);
//
//            // Neue Kategorien hinzufügen
//            foreach ($add_categories as $category_name) {
//                $category_query = '
//            INSERT INTO rex_event_category
//                (name_' . $this->getParam('clang_id') . ', createdate, updatedate, createuser, updateuser)
//            VALUES
//                (:name, :createdate, :updatedate, :createuser, :updateuser)';
//
//                $values                = [];
//                $values[':name']       = $category_name;
//                $values[':createdate'] = date("Y-m-d H:i:s", strtotime($vEvent['DTSTAMP'])); // TODO: Ist es wirklich immer DTSTAMP? Ist die Uhrzeit korrekt?
//                $values[':updatedate'] = date("Y-m-d H:i:s", strtotime($vEvent['DTSTAMP']));
//                $values[':createuser'] = "Cronjob";
//                $values[':updateuser'] = "Cronjob";
//
//                $debugData['insert_category'][] = rex_sql::factory()->setDebug(0)->setQuery($category_query, $values);
//            }
//        }
//
//        // Wenn Option "Remove" gesetzt ist, werden überschüssige Kategorien gelöscht
//        if ($this->getParam('category_sync') === 'remove') {
//            $debugData['$remove_categories'] = $remove_categories = array_diff($existing_categories_names, $category_names_per_event);
//
//            foreach ($remove_categories as $remove_category) {
//                $category_query                  = 'DELETE FROM rex_event_category WHERE name_' . $this->getParam('clang_id') . ' = :name';
//                $debugData['remove_category'][] = rex_sql::factory()->setDebug(0)->setQuery($category_query, [":name" => $remove_category]);
//            }
//        }
//
//        // Neue hinzugefügte Kategorien berücksichtigen
//        $existing_categories                    = rex_sql::factory()->getArray('SELECT id, name_' . $this->getParam('clang_id') . ' AS name FROM `rex_event_category`');
//        $debugData['$existing_categories new'] = $existing_categories;
//
//        // aktuelle Locations herausfinden
//        $existing_locations                = rex_sql::factory()->getArray('SELECT id, name_' . $this->getParam('clang_id') . ' AS name FROM `rex_event_location`');
//        $debugData['$existing_locations'] = $existing_locations;
//        // TODO: Gleiche Überprüfung der Locations wie mit Kategorien + Parsen der Adresse


            // Termine einfügen und aktualisieren
            // $i = 0; // nur für debugging
            //$debugData['$vEvents'] = $ical->events();
            foreach ($ical->events() as $event) {
                $dataset = event_date::getByUID($event->uid);

                if (!$dataset) {
                    $dataset = event_date::create();
                }

                $names     = explode($this->getParam('strip_char'), trim($event->summary));
                $teasers   = explode($this->getParam('strip_char'), trim($event->description));
                $locations = explode($this->getParam('strip_char'), trim($event->location));

                // $i++;
                // $debugData['Event_'.$i] = $vEvent;
//                $categoryIds = [];
//                if ($this->getParam('category_sync') === 'default') {
//                    $categoryIds[] = $this->getParam('category_id');
//                } else {
//                    foreach ($existing_categories as $existing_category) {
//                        if ($id = array_search($existing_category['name'], explode(",", $vEvent['CATEGORIES']))) {
//                            $categoryIds[] = $id;
//                        }
//                    }
//                }

                if ($this->getParam('category_sync') === 'default') {
                    $categoryIds = [$this->getParam('category_id')];
                }
                if ($this->getParam('location_id')) {
                    $locationId = $this->getParam('location_id');
                } else if ('' != trim($event->location)) {
                    $location = event_location::findByRawName($event->location);

                    if (!$location) {
                        $location = event_location::create();
                        $result   = rex_var::toArray(\events\Mapbox::forwardGeocode($event->location, ['language' => implode(',', $langCodes)]));

                        if (isset($result['features'][0])) {
                            foreach (rex_clang::getAll(true) as $index => $lang) {
                                $location->setValue("name_{$lang->getId()}", current(explode(',', $locations[$index] ?? $locations[0])));
                            }
                            $location->setValue('lat', (float)$result['features'][0]['geometry']['coordinates'][1]);
                            $location->setValue('lng', (float)$result['features'][0]['geometry']['coordinates'][0]);
                            $location->setValue("locality_{$lang->getId()}", $result['features'][0]['place_name']);
                            $location->setValue('raw_result', $result['features'][0]);
                            $location->setValue('raw_name', $event->location);

                            foreach ($result['features'][0]['context'] as $context) {
                                [$_type] = explode('.', $context['id']);

                                switch ($_type) {
                                    case 'postcode':
                                        $location->setValue('zip', $context['text']);
                                        break;
                                    case 'country':
                                        $location->setValue('countrycode', $context['short_code']);
                                        break;
                                    case 'place':
                                        foreach ($langCodes as $langId => $langCode) {
                                            $location->setValue("locality_{$langId}", $context['text_' . $langCode]);
                                        }
                                        break;
                                }
                            }

                            if ($location->save()) {
                                $locationId = $location->getId();
                            }
                        }
                    } else {
                        $locationId = $location->getId();
                    }
                } else {
                    $locationId = 0;
                }

                $dtStart    = strtotime($event->dtstart);
                $dtEnd      = strtotime($event->dtend);
                $isFulltime = !(bool)($dtEnd - strtotime($event->dtstart . "+ 1 DAY"));  // Dirty Hack - ganztägige Ereignisse sind von 00:00 bis 00:00 des Folgetages;

                $field  = $dataset->getFields(['name' => 'start', 'type_id' => 'value'])[0];
                $format = strtr($field->getElement('format'), ['DD' => 'd', 'MM' => 'm', 'YYYY' => 'Y', 'HH' => 'H', 'ii' => 'i', 'ss' => 's']);
                $dataset->setValue('start', date($format, $dtStart));

                $field  = $dataset->getFields(['name' => 'end', 'type_id' => 'value'])[0];
                $format = strtr($field->getElement('format'), ['DD' => 'd', 'MM' => 'm', 'YYYY' => 'Y', 'HH' => 'H', 'ii' => 'i', 'ss' => 's']);
                // wenn fulltime Abkehr von ics-Konvention weil Events diese als mehrtägig darstellt.
                if ($isFulltime) {
                    $dataset->setValue('end', date($format, strtotime($event->dtend - 1)));
                } else {
                    $dataset->setValue('end', date($format, $dtEnd));
                }

                foreach (rex_clang::getAll(true) as $index => $lang) {
                    $dataset->setValue("name_{$lang->getId()}", trim($names[$index] ?? $names[0]));
                    $dataset->setValue("teaser_{$lang->getId()}", trim($teasers[$index] ?? $teasers[0]));
                }

                $dataset->setValue('location', $locationId);
                $dataset->setValue('all_day', $isFulltime);
                $dataset->setValue('uid', $event->uid);
                $dataset->setValue('raw', $event);
                $dataset->setValue('source_url', trim($this->getParam('url')));
                $dataset->setValue('url', $event->url);
                $dataset->setValue('categories', implode(',', array_unique($categoryIds)));
                $dataset->setValue('eventStatus', 1);
                $dataset->setValue('image', '');

                if (property_exists($event, 'RRULE')) {
                    // repeating
                    $rrules = [];
                    // Explode RRULE into assoc array, e.g. from FREQ=WEEKLY;BYDAY=FR;UNTIL=20191102T000000
                    foreach (explode(";", $event->rrule) as $item) {
                        [$_key, $_value] = explode('=', $item, 2);
                        $rrules[$_key] = $_value;
                    }
                    $dataset->setValue('type', 'repeat');
                    $dataset->setValue('repeat_year', 1);
                    $dataset->setValue('repeat_week', 1);
                    $dataset->setValue('repeat_month', 1);
                    $dataset->setValue('repeat', strtolower($rrules['FREQ']));
                    $dataset->setValue('end_repeat_date', date("Y-m-d", strtotime($rrules['UNTIL'])));
                } else {
                    $dataset->setValue('type', 'one_time');
                    $dataset->setValue('repeat_year', null);
                    $dataset->setValue('repeat_week', null);
                    $dataset->setValue('repeat_month', null);
                    $dataset->setValue('repeat', null);
                    $dataset->setValue('end_repeat_date', null);
                }
                $debugData['datasets'][$event->uid] = $dataset->getData();

                if ($dataset->save()) {
                    $successCounter++;
                    $savedIds[] = $dataset->getId();
                } else {
                    $debugData['queryErrors'][$event->uid] = $dataset->getMessages();
                    $log = rex_logger::factory();
                    $log->log(E_STRICT, 'Event-import: '. implode("\n", $dataset->getMessages()));
                    $errorCounter++;
                }

                $updatedCategories = array_unique(array_merge($updatedCategories, $categoryIds));
            }
        }
        // Debug-Ausgabe
        if (0 != $this->getParam('debug')) {
            dump($debugData);
        }

        $where = ['uid <> ""'];
        if (count($savedIds)) {
            $where[] = 'id NOT IN(' . implode(',', $savedIds) . ')';
        }
        if (count($categoryIds)) {
            $_where = [];
            foreach ($categoryIds as $categoryId) {
                $_where[] = "FIND_IN_SET('{$categoryId}', categories)";
            }
            $where[] = '('. implode(' OR ', $_where) .')';
        }

        $sql = rex_sql::factory();
        $sql->setTable(rex::getTable(event_date::TABLE));
        $sql->setValue('eventStatus', -1);
        $sql->setWhere(implode(' AND ', $where));
        $sql->update();
        $deleteCounter = $sql->getRows();


        $message = strtr(rex_i18n::msg('events_ics_import_message'), [
            '{{SUCCESS}}' => (int)$successCounter,
            '{{ERROR}}'   => (int)$errorCounter,
            '{{DELETED}}' => (int)$deleteCounter,
        ]);
        $this->setMessage($message);

        // Richtigen Status zurückgeben und Meldung im Backend einfärben
        return $errorCounter == 0;
    }

    public function getTypeName()
    {
        return rex_i18n::msg('events_ics_import_cronjob_name');
    }

    public function getParamFields()
    {
        // ICS-Datei als Demo vorschlagen
        $default_url = 'https://www.schulferien.org/deutschland/ical/download/?lid=81&j=' . date("Y") . '&t=2';

        // Auswahl für REDAXO-Sprachen zusammenzustellen
        $clangs    = rex_clang::getAll();
        $clang_ids = [];
        foreach ($clangs as $clang) {
            $clang_ids[$clang->getValue('id')] = $clang->getValue('name');
        }

        // Benutzerdefinierte Standard-Kategorie auswählen
        $sql_categories = rex_sql::factory()->setDebug(0)->getArray('SELECT id, name_1 AS name FROM ' . rex::getTable(event_category::TABLE));

        $events_category_ids    = [];
        $events_category_ids[0] = rex_i18n::msg('events_ics_import_cronjob_choose');

        foreach ($sql_categories as $sql_category) {
            $events_category_ids[$sql_category['id']] = $sql_category['name'];
        }

        // Benutzerdefinierte Standard-Location auswählen
        $sql_locations          = rex_sql::factory()->setDebug(0)->getArray('SELECT id, name_1 AS name FROM ' . rex::getTable(event_location::TABLE));
        $events_location_ids    = [];
        $events_location_ids[0] = rex_i18n::msg('events_ics_import_cronjob_choose_none');

        foreach ($sql_locations as $sql_location) {
            $events_location_ids[$sql_location['id']] = $sql_location['name'];
        }

        // Eingabefelder des Cronjobs definieren
        $fields = [
            [
                'label'   => rex_i18n::msg('events_ics_import_cronjob_url_label'),
                'name'    => 'url',
                'type'    => 'text',
                'default' => $default_url,
                'notice'  => rex_i18n::msg('events_ics_import_cronjob_url_notice'),
            ],
            [
                'label'   => rex_i18n::msg('events_ics_import_cronjob_beforetoday_label'),
                'name'    => 'beforetoday',
                'type'    => 'text',
                'default' => 30,
                'notice'  => rex_i18n::msg('events_ics_import_cronjob_beforetoday_notice'),
            ],
            [
                'name'    => 'category_sync',
                'label'   => 'Kategorie-Optionen',
                'type'    => 'select',
                'default' => 'keep',
                'options' => [
                    'remove'  => rex_i18n::msg('events_ics_import_cronjob_category_remove'),
                    'default' => rex_i18n::msg('events_ics_import_cronjob_category_default_id'),
                    'keep'    => rex_i18n::msg('events_ics_import_cronjob_category_keep'),
                ],
                'notice'  => rex_i18n::msg('events_ics_import_cronjob_category_sync'),
            ],
            [
                'name'    => 'category_id',
                'type'    => 'select',
                'default' => $sql_categories[0]['id'],
                'options' => $events_category_ids,
                'notice'  => rex_i18n::msg('events_ics_import_cronjob_default_category_sync_id_notice'),
            ],
            [
                'name'    => 'location_id',
                'type'    => 'select',
                'label'   => 'Standard-Location',
                'default' => $sql_locations[0]['id'],
                'options' => $events_location_ids,
                'notice'  => rex_i18n::msg('events_ics_import_cronjob_default_location_sync_id_notice'),
            ],
            [
                'name'    => 'strip_char',
                'type'    => 'text',
                'label'   => rex_i18n::msg('events_ics_strip_char'),
                'default' => '///',
                'notice'  => rex_i18n::msg('events_ics_strip_char_notice'),
            ],
            //            [
            //                'name'    => 'clang_id',
            //                'type'    => 'select',
            //                'label'   => 'Sprache',
            //                'default' => rex_clang::getCurrentId(),
            //                'options' => $clang_ids,
            //                'notice'  => rex_i18n::msg('events_ics_import_cronjob_clang_id_notice'),
            //            ],
            [
                'name'    => 'geocoding',
                'type'    => 'checkbox',
                'default' => 0,
                'options' => [1 => rex_i18n::msg('events_ics_import_cronjob_geocoding')], // TODO: Geocodierung umsetzen
                'notice'  => rex_i18n::msg('events_ics_import_cronjob_geocoding_notice'),
            ],
            [
                'name'    => 'debug',
                'type'    => 'checkbox',
                'default' => 0,
                'options' => [1 => rex_i18n::msg('events_ics_import_cronjob_debug')],
                'notice'  => rex_i18n::msg('events_ics_import_cronjob_debug_notice'),
            ],
        ];

        return $fields;
    }
}
