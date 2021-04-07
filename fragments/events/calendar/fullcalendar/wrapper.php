<?php

/**
 * @author Kreatif GmbH
 * @author a.platter@kreatif.it
 * Date: 07.04.21
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('fullcalendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            //initialDate: '2021-03-07',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: '<?= \rex_url::frontendController(['rex-api-call' => 'fullcalendar']) ?>'
            // [
            //     {
            //         title: 'All Day Event',
            //         start: '2021-03-01'
            //     },
            //     {
            //         title: 'Long Event',
            //         start: '2021-03-07',
            //         end: '2021-03-10'
            //     },
            //     {
            //         groupId: '999',
            //         title: 'Repeating Event',
            //         start: '2021-03-09T16:00:00'
            //     },
            //     {
            //         groupId: '999',
            //         title: 'Repeating Event',
            //         start: '2021-03-16T16:00:00'
            //     },
            //     {
            //         title: 'Conference',
            //         start: '2021-03-11',
            //         end: '2021-03-13'
            //     },
            //     {
            //         title: 'Meeting',
            //         start: '2021-03-12T10:30:00',
            //         end: '2021-03-12T12:30:00'
            //     },
            //     {
            //         title: 'Lunch',
            //         start: '2021-03-12T12:00:00'
            //     },
            //     {
            //         title: 'Meeting',
            //         start: '2021-03-12T14:30:00'
            //     },
            //     {
            //         title: 'Birthday Party',
            //         start: '2021-03-13T07:00:00'
            //     },
            //     {
            //         title: 'Click for Google',
            //         url: 'http://google.com/',
            //         start: '2021-03-28'
            //     }
            // ]
        });

        calendar.render();
    });
</script>
<div id="fullcalendar"></div>
