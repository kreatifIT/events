<?php

class rex_api_fullcalendar extends rex_api_function
{
    protected $published = true;

    public function execute()
    {
        $action = rex_request('action', 'string');

        if ($action == 'get-events') {
            $this->getEvents();
        } else if ($action == 'save-event') {
            $this->saveEvent();
        }
    }

    private function getEvents()
    {
        $start  = rex_get('start', 'string');
        $end    = rex_get('end', 'string');
        $langId = rex_get('lang_id', 'int', rex_clang::getCurrentId());

        $query = event_date::query();
        $query->where('start', $start, '>=');
        $query->where('end', $end, '<=');
        $events = $query->find();

        foreach ($events as $event) {
            $result[] = rex_extension::registerPoint(new rex_extension_point('events.fullcalendar_event', [
                'dataId' => $event->getId(),
                'title'  => $event->getValue("name_{$langId}"),
                'start'  => date('c', strtotime($event->getValue('start'))),
                'end'    => date('c', strtotime($event->getValue('end'))),
                'url'    => html_entity_decode(rex_url::backendPage('events/date', [
                    'table_name' => rex::getTable(event_date::TABLE),
                    'data_id'    => $event->getId(),
                    'func'       => 'edit',
                ])),
            ], [
                'dataset' => $event,
            ]));;
        }

        $result = rex_extension::registerPoint(new rex_extension_point('events.fullcalendar_api_results', $result, [
            'start' => $start,
            'end'   => $end,
        ]));

        header('Content-Type: application/json; charset=UTF-8');
        exit(json_encode($result));
    }

    private function saveEvent()
    {
        $event   = rex_post('event', 'array', []);
        $eventId = (int)$event['extendedProps']['dataId'];

        if (empty($event)) {
            throw new rex_api_exception('event data is missing');
        }

        $dataset = $eventId ? event_date::get($eventId) : null;

        if (!$dataset) {
            $dataset = event_date::create();
        }

        $dataset->setValue('start', date('YmdHis', strtotime($event['start'])));
        $dataset->setValue('end', date('YmdHis', strtotime($event['end'])));
        $dataset->save();
        header('Content-Type: application/json; charset=UTF-8');
        exit(json_encode(['success' => true]));
    }

    public static function httpError($result)
    {
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json; charset=UTF-8');
        echo "test";
        exit(json_encode($result));
    }
}
