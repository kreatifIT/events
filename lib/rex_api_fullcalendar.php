<?php

class rex_api_fullcalendar extends rex_api_function
{
    protected $published = true;

    public function execute()
    {
        $start  = rex_get('start', 'string');
        $end    = rex_get('end', 'string');
        $langId = rex_get('lang_id', 'int', rex_clang::getCurrentId());

        $query = event_date::query();
        $query->where('start', $start, '>=');
        $query->where('end', $end, '<=');
        $events = $query->find();

        foreach ($events as $event) {
            $result[] = [
                "title" => $event->getValue("name_{$langId}"),
                "start" => date('c', strtotime($event->getValue('start'))),
                "end"   => date('c', strtotime($event->getValue('end'))),
                //                "url"   => "/redaxo/index.php?page=events/date&table_name=rex_event_date&rex_yform_manager_popup=0&data_id=" . $event->id . "&func=edit",
            ];
        }

        header('Content-Type: application/json; charset=UTF-8');
        exit(json_encode($result));
    }

    public static function httpError($result)
    {
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json; charset=UTF-8');
        echo "test";
        exit(json_encode($result));
    }
}
