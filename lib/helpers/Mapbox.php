<?php

/**
 * This file is part of the Kreatif\Project package.
 *
 * @author Kreatif GmbH
 * @author a.platter@kreatif.it
 * Date: 14.12.20
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace events;

class Mapbox
{
    /**
     * https://docs.mapbox.com/api/search/geocoding/
     * https://docs.mapbox.com/help/troubleshooting/address-geocoding-format-guide/
     *
     * @param       $searchTerm
     *                                 address-format: {house number} {street} {zip} {city} {state}
     * @param array $getParams
     *                                 country => ISO2
     *                                 language => ISO 639-1 https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes
     *                                 limit => int
     *                                 types => country, region, postcode, district, place, locality, neighborhood, address, and poi
     *
     * @return bool|string
     */
    public static function forwardGeocode($searchTerm, $getParams = [])
    {
        $searchTerm                = urlencode($searchTerm);
        $getParams['access_token'] = \rex_config::get('events', 'mapbox_api_code');
        return file_get_contents('https://api.mapbox.com/geocoding/v5/mapbox.places/' . $searchTerm . '.json?' . htmlspecialchars_decode(http_build_query($getParams)));
    }
}