<?php

/**
 * yform
 * @author Kreatif GmbH
 * @author <a href="http://www.kreatif.it">www.kreatif.it</a>
 */
class rex_yform_value_events_tab_start extends rex_yform_value_abstract
{

    function enterObject()
    {
        ob_start();
        ?>
        <div class="nav rex-page-nav yform-tabs">
        <ul class="nav nav-tabs tabs" data-tabs id="form-tab-content-<?= $this->params['this']->getObjectparams('main_id') ?>">
            <?php
            $clangs = rex_clang::getAll();
            foreach ($clangs as $clang) {
                echo '<li class="tabs-title ' . ($clang->getId() == rex_clang::getCurrentId() ? 'active is-active' : '') . '"><a href="#form-tab-content-' . $this->params['this']->getObjectparams('main_id') . '-col-' . $clang->getId() . '" data-toggle="tab">' . $clang->getName() . '</a></li>';
            }
            ?>
        </ul>

    <div class="tab-content tabs-content lang-tab-content" data-tabs-content="form-tab-content-<?= $this->params['this']->getObjectparams('main_id') ?>">
            <div class="tab-pane fade in active is-active tabs-panel">
        <?php
        $out = ob_get_contents();
        ob_end_clean();
        $this->params['form_output'][$this->getId()] = $out;
    }

    function getDescription()
    {
        return htmlspecialchars('events_tab_start');
    }

    function getDefinitions($values = [])
    {
        return [
            'type'            => 'value',
            'name'            => 'events_tab_start',
            'values'          => [
                'name' => ['type' => 'name', 'label' => rex_i18n::msg("yform_values_defaults_name")],
            ],
            'description'     => rex_i18n::msg("yform_values_tab_start_description"),
            'dbtype'          => 'none',
            'is_hiddeninlist' => true,
            'is_searchable'   => false,
            'famous'          => false,
            'multi_edit'      => 'always',
        ];
    }

}
