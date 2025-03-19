# Slideshow\_XH

Slideshow\_XH ermöglicht die Anzeige aller Bilder
in einem angegeben Ordner als Diashow.
Diese Diashows erlauben keinerlei Interaktion durch den Benutzer.
Mehrere Diashows mit unterschiedlichen Effekten
und Zeitabläufen sind möglich.

- [Voraussetzungen](#voraussetzungen)
- [Download](#download)
- [Installation](#installation)
- [Einstellungen](#einstellungen)
- [Verwendung](#verwendung)
  - [Beispiele](#beispiele)
- [Einschränkungen](#einschränkungen)
- [Problembehebung](#problembehebung)
- [Lizenz](#lizenz)
- [Danksagung](#danksagung)

## Voraussetzungen

Slideshow_XH ist ein Plugin für  [CMSimple_XH](https://www.cmsimple-xh.org/de/).
Es benötigt CMSimple_XH ≥ 1.7.0 und PHP ≥ 7.1.0.
Slideshow_XH benötigt weiterhin [Plib_XH](https://github.com/cmb69/plib_xh) ≥ 1.2;
ist dieses noch nicht installiert (siehe `Einstellungen` → `Info`),
laden Sie das [aktuelle Release](https://github.com/cmb69/plib_xh/releases/latest)
herunter, und installieren Sie es.

## Download

Das [aktuelle Release](https://github.com/cmb69/slideshow_xh/releases/latest)
kann von Github herunter geladen werden.

## Installation

Die Installation erfolgt wie bei vielen anderen CMSimple\_XH-Plugins auch.

1. Sichern Sie die Daten auf Ihrem Server.
1. Entpacken Sie die ZIP-Datei auf Ihrem Rechner.
1. Laden Sie das ganze Verzeichnis `slideshow/` auf Ihren Server
   in das `plugins/` Verzeichnis von CMSimple\_XH hoch.
1. Vergeben Sie falls nötig Schreibrechte für die Unterverzeichnisse
   `config/` und `languages/`.
1. Navigieren Sie zu `Plugins` → `Slideshow` im Administrationsbereich,
   um zu prüfen, ob alle Voraussetzungen erfüllt sind.

## Einstellungen

Die Plugin-Konfiguration erfolgt wie bei vielen anderen
CMSimple\_XH-Plugins auch im Administrationsbereich der Website.
Gehen Sie zu `Plugins` → `Slideshow`.

Sie können die Voreinstellungen von Slideshow\_XH unter `Konfiguration` ändern.
Beim Überfahren der Hilfe-Icons mit der Maus
werden Hinweise zu den Einstellungen angezeigt.

Die Lokalisierung wird unter `Sprache` vorgenommen.
Sie können die Sprachtexte in Ihre eigene Sprache übersetzen,
falls keine entsprechende Sprachdatei zur Verfügung steht,
oder diese Ihren Wünschen gemäß anpassen.

## Verwendung

Um eine Diashow auf allen Seiten anzuzeigen, fügen sie ins Template ein:

    <?=slideshow('ORDNER', 'OPTIONEN')?>

Um eine Diashow auf einer einzelnen Seite oder in einer Newsbox anzuzeigen,
geben Sie auf der Seite ein:

    {{{slideshow('ORDNER', 'OPTIONEN')}}}

Die Parameter haben die folgende Bedeutung:
- `ORDNER`:
  Der Pfad eines Ordners relativ zum Bilderordner von CMSimple\_XH.
  Alle JPEG, PNG und GIF Bilder in diesem Ordner
  werden für die Diashow verwendet;
  es müssen sich mindestens zwei Bilder in diesem Ordner befinden.
  Existieren ebenfalls `*.webp` Bilder mit demselben Basisnamen wie ein erkanntes Bild,
  werden diese statt dessen verwendet, falls der Browser WebP unterstützt.
  Alle Bilder sollten das gleiche Seitenverhältnis aufweisen.
- `OPTIONEN`:
  Jede angegebene Option überschreibt die entsprechende Voreinstellung
  im `Default` Abschnitt der Pluginkonfiguration.
  Das Format dieses Parameters ist das gleiche wie bei einem „Query-String“
  (siehe die Beispiele weiter unten).
  Die Optionen können in beliebiger Reihenfolge angegeben werden.
  Wenn Sie bei den Voreinstellungen bleiben wollen,
  können Sie diesen Parameter auslassen.
  Die folgenden Optionen können angegeben werden:
  - `order`:
    Die Reihenfolge der Bilder:
    `fixed` (alphabetisch sortiert; beginnt mit dem ersten Bild),
    `sorted` (alphabetisch sortiert; beginnt mit einem zufällig gewählten Bild)
    oder `random` (zufällig geordnet).
  - `effect`:
    Die Art des Übergangs: `fade`, `slide`, `curtain` oder `random`.
  - `easing`:
    Der Beschleunigungseffekt: `linear`, `easeIn`, `easeOut` oder `easeInOut`.
  - `delay`:
    Die Verzögerung in Millisekunden bis zum ersten Start der Diashow.
  - `pause`:
    Die Dauer der Pause zwischen den Übergängen in Millisekunden.
  - `duration`:
    Die Dauer des Übergangseffekts in Millisekunden.

### Beispiele

Zum Anzeigen der Bilder in `userfiles/images/banner/`
mit den Voreinstellungen auf jeder Seite:

    <?=slideshow('banner')?>

Zum Anzeigen der Bilder in der Art eines Fließbands:

    {{{slideshow('slides/fliessband/', 'effect=slide&amp;pause=0&amp;duration=2000')}}}

Zum Anzeigen der Bilder als ruhige, langsam überblendende Diashow:

    {{{slideshow('slides/ruhig/', 'effect=fade&amp;pause=5000&amp;duration=100')}}}

Zum Anzeigen geigneter Bilder als Daumenkino:

    {{{slideshow('slides/daumenkino/', 'order=fixed&amp;effect=fade&amp;pause=100&amp;duration=100')}}}

## Einschränkungen

Die Diashows werden nur abgespielt,
wenn JS im Browser der Besucher aktiviert ist,
und sie einen zeitgemäßen Browser verwenden,
Ansonsten wird nur das erste Bild angezeigt.
Die Übergangseffekte sind rechenintensiv,
besonders für große Bilder.
Daher sollten Sie sich auf nur wenige Diashows
mit kleinen oder mittelgroßen Bildern auf der selben Seite beschränken,
um Besuchern mit geringer Rechenleistung
keine stotternden Diashows zu präsentieren.

## Problembehebung

Melden Sie Programmfehler und stellen Sie Supportanfragen entweder auf
[Github](https://github.com/cmb69/slideshow_xh/issues)
oder im [CMSimple\_XH Forum](https://cmsimpleforum.com/).

## Lizenz

Slideshow\_XH ist freie Software. Sie können es unter den Bedingungen
der GNU General Public License, wie von der Free Software Foundation
veröffentlicht, weitergeben und/oder modifizieren, entweder gemäß
Version 3 der Lizenz oder (nach Ihrer Option) jeder späteren Version.

Die Veröffentlichung von Slideshow\_XH erfolgt in der Hoffnung, daß es
Ihnen von Nutzen sein wird, aber *ohne irgendeine Garantie*, sogar ohne
die implizite Garantie der *Marktreife* oder der *Verwendbarkeit für einen
bestimmten Zweck*. Details finden Sie in der GNU General Public License.

Sie sollten ein Exemplar der GNU General Public License zusammen mit
Slideshow\_XH erhalten haben. Falls nicht, siehe
<https://www.gnu.org/licenses/>.

Copyright 2012-2021 Christoph M. Becker

Dänische Übersetzung © 2012 Jens Maegard  
Tschechische Übersetzung © 2012-2013 Josef Němec  
Slovakische Übersetzung © 2013 Dr. Martin Sereday  
Französische Übersetzung © 2014 Patrick Varlet

## Danksagung

Slideshow\_XH wurde von *Joe* angeregt.

Das Pluginicon wurde von [Mischa McLachlan](https://twitter.com/Zyote) entworfen.
Vielen Dank für die Veröffentlichung unter einer freien Lizenz.

Many thanks to the community at the
[CMSimple\_XH Forum](https://www.cmsimpleforum.com/)
for tips, suggestions and testing.
Insbesondere vielen Dank an *Caravaner* für das zeitnahe Melden von Fehlern,
und *olape* für das Vorschlagen der progressiven WebP Verbesserung.

Und zu guter letzt vielen Dank an
[Peter Harteg](https://www.harteg.dk/), den „Vater“ von CMSimple,
und allen Entwicklern von [CMSimple\_XH](https://www.cmsimple-xh.org/de/)
ohne die es dieses phantastische CMS nicht gäbe.
