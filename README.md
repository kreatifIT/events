# Terminverwaltung für REDAXO 5.10 & YForm 3.3

Mit diesem Addon können Termine anhand von YForm und YOrm im Backend verwaltet und im Frontend ausgegeben werden. Auf Wunsch auch mehrsprachig.

**Vorschau auf events Version 2.0 im Branch `ics-import`**

![Dateneingabe](https://raw.githubusercontent.com/alexplusde/events/master/docs/events_date_edit.png)

## Features

* Vollständig mit **YForm** umgesetzt: Alle Features und Anpassungsmöglichkeiten von YForm verfügbar
* Einfach: Die Ausgabe erfolgt über [`rex_sql`](https://redaxo.org/doku/master/datenbank-queries) oder objektorientiert über [YOrm](https://github.com/yakamara/redaxo_yform_docs/blob/master/de_de/yorm.md)
* Flexibel: **Zugriff** über die [YForm Rest-API](https://github.com/yakamara/redaxo_yform/blob/master/docs/plugins.md#restful-api-einf%C3%BChrung)
* Sinnvoll: Nur ausgewählte **Rollen**/Redakteure haben Zugriff
* Bereit für **mehrsprachige** Websites: Reiter für Sprachen auf Wunsch anzeigen oder ausblenden
* Bereit für mehr: Vorbereitet für das [JSON+LD-Format](https://jsonld.com/event/), ICS-Format
* Bereit für viel mehr: Kompatibel zum [URL2-Addon](https://github.com/tbaddade/redaxo_url)

> **Tipp:** Events arbeitet hervorragend zusammen mit den Addons [`yform_usability`](https://github.com/FriendsOfREDAXO/yform_usability/) und [`yform_geo_osm`](https://github.com/FriendsOfREDAXO/yform_geo_osm)

> **Steuere eigene Verbesserungen** dem [GitHub-Repository von events](https://github.com/alexplusde/events) bei. Oder **unterstütze dieses Addon:** Mit einer [Spende oder Beauftragung unterstützt du die Weiterentwicklung dieses AddOns](https://github.com/sponsors/alexplusde)

![Mehrspachigkeit](https://raw.githubusercontent.com/alexplusde/events/master/docs/events_date_multilang.png)

![Mehrspachigkeit](https://raw.githubusercontent.com/alexplusde/events/master/docs/events_location.png)

## Installation

Im REDAXO-Installer das Addon `events` herunterladen und installieren. Anschließend erscheint ein neuer Menüpunkt `Veranstaltungen` sichtbar.

## Nutzung im Frontend

### Die Klasse `event_date`

Typ `rex_yform_manager_dataset`. Greift auf die Tabelle `rex_event_date` zu.

#### Beispiel-Ausgabe eines Termins

```php
dump(event_date::get(3)); // Termin mit der id=3
```

#### Zusätzliche Methoden
| Methode                       | Beschreibung                                                                                                                         |
|-------------------------------|--------------------------------------------------------------------------------------------------------------------------------------|
| `getCategory()`               | holt die passende Kategorie als `event_category`-Dataset.                                                                            |
| `getIcs()`                    | gibt eine ICS-Datei zur Veranstaltung zurück                                                                                         |
| `getLocation()`               | holt den passenden Veranstaltungsort als `event_location`-Dataset.                                                                   |
| `getOfferAll()`               | holt die passenden Angebote / Preise als `event_offer`-Dataset                                                                       |
| `getImage()`                  | gibt den Bild-Dateinamen aus dem Medienpool zurück                                                                                   |
| `getMedia()`                  | gibt ein REDAXO-Medienobjekt des Bildes zurück                                                                                       |
| `getDescriptionAsPlaintext()` | gibt die Veranstaltungsbeschreibung als Plaintext zurück                                                                             |
| `getIcsStatus()`              | gibt den Status zurück, wie er im ICS-Format erwartet wird.                                                                          |
| `getUid()`                    | gibt eine UID zurück, wie sie im ICS-Format erwartet wird. Wenn es die UID noch nicht gibt, wird sie automatisch erzeugt.            |
| `getJsonLd()`                 | gibt den JSON-LD-Code zur Veranstaltung zurück                                                                                       |
| `getStartDate()`              | gibt ein DateTime-Objekt zurück mit dem korrekten Startdatum in Abhängigkeit von den gewählten Optionen (ganztägig)                  |
| `getEndDate()`                | gibt ein DateTime-Objekt zurück mit dem korrekten Enddatum, sofern vorhanden, in Abhängigkeit von den gewählten Optionen (ganztägig) |
|                               |                                                                                                                                      |
|                               |                                                                                                                                      |
```php
dump(event_date::get(3)->getCategory()); // Kategorie des Termins mit der id=3
```

### Die Klasse `event_category`

Typ `rex_yform_manager_dataset`. Greift auf die Tabelle `rex_event_category` zu.

#### Beispiel-Ausgabe einer Kategorie

```php
dump(event_category::get(3)); // Kategorie mit der id=3
```

### Die Klasse `event_location`

Typ `rex_yform_manager_dataset`. Greift auf die Tabelle `rex_event_location_` zu.

#### Beispiel-Ausgabe einer Location

```php
dump(event_location::get(3)); // Location mit der id=3
```

#### Zusätzliche Methoden
| Methode                       | Beschreibung                                                                                                                         |
|-------------------------------|--------------------------------------------------------------------------------------------------------------------------------------|
| `getLocationAsString()`               | holt die passende Adresse als string in der Form Straße, PLZ, Ort,  Land                                                                          |
| `getLocationName()`                    | gibt den Namen der Location zurück                                                                                         |
| `getLocationStreet()`               | gibt die Straße der Location zurück                                                                  |
| `getLocationZip()`               | gibt die Postleitzahl der Location zurück                                                                       |
| `getLocationLocality()`                  | gibt den Ortsnamen der Location zurück                                                                                  |
| `getLocationCountrycode()`                  | gibt den Ländercode der Location zurück                                                                                       |
| `getLocationLatLng()` | gibt die Geo-Koordinaten der Location in der Form 'lat,lang' zurück (noch nicht implementiert)                                                                          |
| `getLocationLat()`              | gibt die Geo-Koordinate latitude der Location zurück.                                                                          |
| `getLocationLng()`                | gibt die Geo-Koordinate longitude der Location zurück. |
|                               |                                                                                                                                      |
|                               |                                                                                                                                      |
```php
dump(event_location::get(3)->getLocationName()); // Name der Location mit der id=3
```
## Nutzung im Backend: Die Terminverwaltung

### Die Tabelle "SPRACHEN"

Die Tabelle "TERMINE" mit Flaggen-Symbol ist eine Tabelle, in der zunächst Sprachen verwaltet werden können und im Anschluss die eigentliche Termin-Tabelle gefiltert nach dieser Sprache angezeigt wird.

Wer keine mehrsprachigen Termine benötigt, kann diesen Menüpunkt problemlos für Redakteure über die Benutzer-Rollen ausblenden. Wichtig ist jedoch, dass mind. eine Sprache angelegt wurde.

### Die Tabelle "TERMINE"

In der Termin-Tabelle werden einzelne Daten festgehalten. Nach der Installation von `events` stehen folgende Felder zur Verfügung:

| Typ      | Typname             | Name                | Bezeichnung       |
|----------|---------------------|---------------------|-------------------|
| value    | text                | name                | Name              |
| validate | empty               | name                |                   |
| value    | textarea            | description         | Beschreibung      |
| value    | be_manager_relation | event_category_id   | Kategorie         |
| value    | be_manager_relation | location            | Veranstaltungsort |
| value    | be_media            | image               | Bild              |
| value    | text                | url                 | URL               |
| value    | datetime            | startDate           | Beginn            |
| validate | compare_value       | startDate           |                   |
| value    | time                | doorTime            | Einlass           |
| value    | datetime            | endDate             | Ende              |
| value    | select              | eventStatus         | Status            |
| value    | text                | offers_url          | Tickets-URL       |
| value    | text                | offers_price        | Preis             |
| validate | type                | offers_price        |                   |
| value    | select              | offers_availability | Verfügbarkeit     |
| validate | type                | url                 |                   |

Die Felder und Feldnamen orientieren sich dabei am [JSON+LD-Standard für Veranstaltungen](https://jsonld.com/event/), die wichtigsten Validierungen wurden bereits eingefügt.

### Die Tabelle "KATEGORIEN"

Die Tabelle Kategorien kann frei verändert werden, um Termine zu gruppieren (bspw. Veranstaltungsreihen) oder zu Verschlagworten (als Tags).

| Typ      | Typname             | Name    | Bezeichnung |
|----------|---------------------|---------|-------------|
| value    | text                | name    | Titel       |
| validate | unique              | name    |             |
| validate | empty               | name    |             |
| value    | be_media            | image   | Bildmotiv   |
| value    | choice              | status  | Status      |
| value    | be_manager_relation | date_id | Termine     |

### Die Tabelle "LOCATION"

Die Tabelle Location enthält die passenden Veranstaltungsorte zu den Veranstaltungen. Sie wurde im Hinblick auf leichte Geocodierung erstellt, lässt sich aber beliebig um zusätzliche Informationen erweitern.

| Typname | Name        | Bezeichnung | Funktion           |
|---------|-------------|-------------|--------------------|
| value   | text        | name        | Name               |
| value   | text        | street      | Straße, Hausnummer |
| value   | text        | zip         | PLZ                |
| value   | text        | locality    | Stadt              |
| value   | osm_geocode | lat_lng     | Geoposition        |
| value   | text        | lat         | Latitude           |
| value   | text        | lng         | Lng                |

Die Felder und Feldnamen orientieren sich dabei am [JSON+LD-Standard für Veranstaltungen](https://jsonld.com/event/), die wichtigsten Validierungen wurden bereits eingefügt.

## RESTful API (dev)

Die [Rest-API](https://github.com/yakamara/redaxo_yform/blob/master/docs/plugins.md#restful-api-einf%C3%BChrung) ist über das REST-Plugin von YForm umgesetzt.

### Einrichtung

Zunächst das REST-Plugin von YForm installieren und einen Token einrichten. Den Token auf die jeweiligen Endpunkte legen:

```php
    /v0.dev/event/date
    /v0.dev/event/category
    /v0.dev/event/location
```

### Endpunkt `date`

**Auslesen:** GET `example.org/rest/v0.dev/event/date/?token=###TOKEN###`

**Auslesen einzelner Termin**  GET `example.org/rest/v0.dev/event/date/7/?token=###TOKEN###` Termin  der `id=7`

### Endpunkt `category`

**Auslesen:** GET `example.org/rest/v0.dev/event/category/?token=###TOKEN###`

**Auslesen einzelne Kategorie**  GET `example.org/rest/v0.dev/event/category/7/?token=###TOKEN###` Termin  der `id=7`

### Endpunkt `location`

**Auslesen:** GET `example.org/rest/v0.dev/event/location/?token=###TOKEN###`

**Auslesen einzelner Standort**  GET `example.org/rest/v0.dev/event/location/7/?token=###TOKEN###` Termin  der `id=7`

## Import

### Import von ICS-Kalendern (dev)

Events kommt mit einem eigenen Cronjob zum importieren von ics-Kalendern aus dem Internet. Das Cronjob-Addon aufrufen, einen neuen Cronjob anlegen und den Instruktionen folgen.

## Export

## Export eines einzelnen Termins als ics-Datei (dev)

Events kommt mit einer eigenen rex_api-Schnittstelle für den Export von einzelnen Terminen. `?rex-api-call=events_ics_file&id=2` aufrufen, um eine ICS-Datei anhand des Termins mit der `id=2` zu erzeugen.

## Lizenz

MIT Lizenz, siehe [LICENSE.md](https://github.com/alexplusde/events/blob/master/LICENSE.md)  

## Autoren

**Alexander Walther**  
http://www.alexplus.de  
https://github.com/alexplusde  

**Michael Schuler**
https://github.com/191977 


**Projekt-Lead**  
[Alexander Walther](https://github.com/alexplusde)

## Credits

events basiert auf: [YForm](https://github.com/yakamara/redaxo_yform)  
Danke an [Gregor Harlan](https://github.com/gharlan) für die Unterstützung
