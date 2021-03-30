<?php

/**
 * yform
 * @author Kreatif GmbH
 * @author <a href="http://www.kreatif.it">www.kreatif.it</a>
 */
class rex_yform_value_events_tab_end extends rex_yform_value_abstract
{

    function enterObject()
    {
        ob_start();
        ?>
        </div>
        </div>
        </div>
        <?php
        $out = ob_get_contents();
        ob_end_clean();
        $this->params['form_output'][$this->getId()] = $out;
    }

    function getDescription()
    {
        return htmlspecialchars('events_tab_end');
    }

    function getDefinitions($values = [])
    {
        return [
            'type'            => 'value',
            'name'            => 'events_tab_end',
            'values'          => [
                'name' => ['type' => 'name', 'label' => rex_i18n::msg("yform_values_defaults_name")],
            ],
            'description'     => rex_i18n::msg("yform_values_tab_end_description"),
            'dbtype'          => 'none',
            'is_hiddeninlist' => true,
            'is_searchable'   => false,
            'famous'          => false,
            'multi_edit'      => 'always',
        ];
    }

}
