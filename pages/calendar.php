<?php

$fragment = new rex_fragment();
$body = $fragment->parse('events/calendar/fullcalendar/wrapper.php');

$fragment = new rex_fragment();
$fragment->setVar('class', 'edit', false);
$fragment->setVar('title', "Kalender-Ansicht", false);
$fragment->setVar('body', $body, false);
echo $fragment->parse('core/page/section.php');