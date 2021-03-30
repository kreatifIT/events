<?php

$targetPage     = rex_request('page', 'string');
$pageProperties = rex_addon::get('events')->getProperty('page')['subpages'];
$pageParts      = rex_be_controller::getCurrentPagePart();

if (isset($pageProperties[$pageParts[1]])) {
    $properties = $pageProperties[$pageParts[1]];
    # yform-properties
    $tableName = $properties['yformTable'] ?? '';
    $wrapper   = $properties['yformClass'] ?? '';
} else {
    $tableName = '';
}

$table = rex_yform_manager_table::get($tableName);

if ($table && rex::getUser() && (rex::getUser()->isAdmin() || rex::getUser()->getComplexPerm('yform_manager_table')->hasPerm($table->getTableName()))) {
    $page = new rex_yform_manager();
    $page->setTable($table);
    $page->setLinkVars(['page' => $targetPage, 'table_name' => $table->getTableName()]);

    try {
        if ($wrapper) {
            echo "<div class=\"$wrapper\">";
        }

        # Seite erzeugen und abfangen
        ob_start();
        $page->getDataPage();
        $page = ob_get_clean();
        # Such den Header - Fall 1: mit Suchspalte?
        $p = strpos($page, '<div class="row">');
        # Such den Header - Fall 2: ohne Suchspalte
        if ($p === false) {
            $p = strpos($page, '<section class="rex-page-section">');
        }
        # Header rauswerfen
        if ($p !== false) {
            $page = '' . substr($page, $p);
        }
        # ausgabe
        echo $page;

        if ($wrapper) {
            echo '</div>';
        }
    } catch (Throwable $e) {
        ob_get_clean();
        $message = nl2br($e->getMessage() . "\n" . $e->getTraceAsString());
        echo rex_view::warning($message);
    }
} else if (!$table) {
    echo rex_view::warning(rex_i18n::msg('yform_table_not_found'));
}
