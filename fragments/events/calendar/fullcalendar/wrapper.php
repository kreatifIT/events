<?php

/**
 * @author Kreatif GmbH
 * @author a.platter@kreatif.it
 * Date: 07.04.21
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 *
 *  Documentation: https://fullcalendar.io/docs
 */

$settings = $this->getVar('settings');


?>
<script>
    (function() {
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('fullcalendar');
            var calendar = new FullCalendar.Calendar(calendarEl, <?= json_encode($settings) ?>);
            calendar.setOption('eventDrop', saveEvent);
            calendar.setOption('eventResize', saveEvent);
            calendar.render();
        });


        function saveEvent(event) {
            $.ajax({
                url: '<?= html_entity_decode(\rex_url::frontendController(['rex-api-call' => 'fullcalendar', 'action' => 'save-event'])) ?>',
                method: 'POST',
                data: {
                    event: event.event.toPlainObject()
                }
            })
                .done(function (resp) {
                });
        }
    })();
</script>
<div id="fullcalendar"></div>
