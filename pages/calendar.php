<?php


$clang = rex_clang::getCurrent();

$settings = rex_extension::registerPoint(new rex_extension_point('events.fullcalendar_settings', [
    'locale'        => $clang->getCode(),
    'editable'      => true,
    'initialView'   => 'timeGridWeek',
    'nowIndicator'  => true,
    'weekNumbers'   => true,
    'initialDate'   => date('Y-m-d'),
    'events'        => html_entity_decode(\rex_url::frontendController(['rex-api-call' => 'fullcalendar', 'action' => 'get-events', 'ts' => time()])),
    'headerToolbar' => [
        'left'   => 'prev,next today',
        'center' => 'title',
        'right'  => 'dayGridMonth,timeGridWeek,timeGridDay',
    ],
]));


$fragment = new rex_fragment();
$fragment->setVar('settings', $settings, false);
$body = $fragment->parse('events/calendar/fullcalendar/wrapper.php');

$fragment = new rex_fragment();
$fragment->setVar('class', 'edit', false);
$fragment->setVar('title', "Kalender-Ansicht", false);
$fragment->setVar('body', $body, false);
echo $fragment->parse('core/page/section.php');