<?php

/**
 * @author Kreatif GmbH
 * @author a.platter@kreatif.it
 * Date: 09.04.21
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace events;


class Settings
{
    public static function getValue(string $key, $default = null)
    {
        return \rex_config::get('events', $key, $default);
    }

}