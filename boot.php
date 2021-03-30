<?php

if (rex::isBackend() && rex_be_controller::getCurrentPage() == 'events/calendar') {
//    rex_view::addJsFile($this->getAssetsUrl('fullcalendar/packages/core/main.js'));
//    rex_view::addCssFile($this->getAssetsUrl('fullcalendar/packages/core/main.css'));
//    rex_view::addJsFile($this->getAssetsUrl('fullcalendar/packages/daygrid/main.js'));
//    rex_view::addCssFile($this->getAssetsUrl('fullcalendar/packages/daygrid/main.css'));
//    rex_view::addJsFile($this->getAssetsUrl('fullcalendar/packages/bootstrap/main.js'));
//    rex_view::addCssFile($this->getAssetsUrl('fullcalendar/packages/bootstrap/main.css'));
//    rex_view::addJsFile($this->getAssetsUrl('fullcalendar/packages/timegrid/main.js'));
//    rex_view::addCssFile($this->getAssetsUrl('fullcalendar/packages/timegrid/main.css'));
//    rex_view::addJsFile($this->getAssetsUrl('fullcalendar/packages/list/main.js'));
//    rex_view::addCssFile($this->getAssetsUrl('fullcalendar/packages/list/main.css'));
//    rex_view::addJsFile($this->getAssetsUrl('fullcalendar/packages/core/locales/de.js'));
//    rex_view::addJsFile($this->getAssetsUrl('backend.js'));
}

rex_yform_manager_dataset::setModelClass(
    'rex_event_date',
    event_date::class
);
rex_yform_manager_dataset::setModelClass(
    'rex_event_location',
    event_location::class
);
rex_yform_manager_dataset::setModelClass(
    'rex_event_category',
    event_category::class
);
rex_yform_manager_dataset::setModelClass(
    'rex_event_date_offer',
    event_date_offer::class
);

if (rex_addon::get('cronjob')->isAvailable() && !rex::isSafeMode()) {
    rex_cronjob_manager::registerType('rex_cronjob_events_ics_import');
}

if (rex_plugin::get('yform', 'rest')->isAvailable() && !rex::isSafeMode()) {
    foreach (glob($this->getPath('lib/rest/*.php')) as $_include) {
        include_once $_include;
    }
}

rex_extension::register('REX_YFORM_SAVED', function (rex_extension_point $ep) {

    // darf nur bei passender Tabelle passieren.
//    $id = $ep->getParam('id');
//    $dataset = event_date::get($ep->getParam('id'));
//    rex_sql::factory()->setQuery("UPDATE rex_event_date SET uid = :uid WHERE id = :id", [":uid"=>$dataset->getUid(), ":id" => $id]);

});