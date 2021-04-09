<?php

$form = rex_config_form::factory('events');

$form->addFieldset('Kalender');
$field = $form->addSelectField('fullcalendar_initial_view');
$field->setLabel('Startansicht');
$select = $field->getSelect();
$select->addOptions([
    'dayGridMonth' => 'Monat',
    'timeGridWeek' => 'Woche',
    'timeGridDay'  => 'Tag',
    'listMonth'     => 'Liste vom Monat',
]);


$form->addFieldset('allgemeine Einstellungen');

$field = $form->addInputField('text', 'currency', null, ["class" => "form-control"]);
$field->setLabel(rex_i18n::msg('events_currency'));

$field = $form->addInputField('text', 'mapbox_api_code', null, ["class" => "form-control"]);
$field->setLabel(rex_i18n::msg('events_mapbox'));
$field->setNotice(rex_i18n::msg('events_mapbox_api_notice') . '<a href="https://www.mapbox.com/studio/account/tokens/">https://www.mapbox.com/studio/account/tokens/</a>');

$field = $form->addInputField('text', 'timezone_api_code', null, ["class" => "form-control"]);
$field->setLabel(rex_i18n::msg('events_timezone'));
$field->setNotice(rex_i18n::msg('events_timezone_notice') . '<a href="https://developers.google.com/maps/documentation/timezone/intro?hl=de">https://developers.google.com/maps/documentation/timezone/intro?hl=de</a>');

$fragment = new rex_fragment();
$fragment->setVar('class', 'edit', false);
$fragment->setVar('title', rex_i18n::msg('events_settings'), false);
$fragment->setVar('body', $form->get(), false);
echo $fragment->parse('core/page/section.php');
