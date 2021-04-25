# Slideshow\_XH

Slideshow\_XH usnadňuje zobrazení všech obrázků v dané složce jako prezentace.
Tyto prezentace neumožňují žádnou interakci návštěvníka.
Použití více prezentací s různými efekty a časováním je možné.

- [Požadavky](#požadavky)
- [Download](#download)
- [Instalace](#instalace)
- [Nastavení](#nastavení)
- [Použití](#použití)
  - [Příklady](#příklady)
- [Omezení](#omezení)
- [Troubleshooting](#troubleshooting)
- [License](#license)
- [Credits](#credits)

## Požadavky

Slideshow\_XH je plugin pro CMSimple\_XH Omezení 1.7.0.
It requires PHP Omezení 5.4.0.

## Download

The [lastest release](https://github.com/cmb69/slideshow_xh/releases/latest)
is available for download on Github.

## Instalace

Instalace se provádí stejně jako u ostatních pluginů CMSimple\_XH.

1. Zálohujte data na svém serveru.
1. Rozbalte distribuční balíček ve svém počítači.
1. Nahrajte celý adresář `slideshow/` na váš server do adresáře `plugins/`
   CMSimple\_XH.
1. Nastavte práva k zápisu pro podadresáře `config/` a `languages/`.
1. Zkontrolujte `Pluginy` → `Slideshow` v režimu administrace zda jsou všechny
   požadavky splněny.

## Nastavení

Konfigurace tohoto pluginu se pravádí stejně jako u ostatních CMSimple\_XH
pluginů v režimu administrace.
Vyberte `Pluginy` → `Slideshow`.

Můžete změnit výchozí nastavení Slideshow\_XH v `Config`.
Tipy pro volby se zobrazí při najetí nad nápovědy ikon s myší.

Lokalizace se provádí v nastavení `Jazyk`.
Můžete přeložit řetězce znaků ve vašem vlastním jazyce
if there is no appropriate language file available,
nebo je upravit podle vašich potřeb.

## Použití

Chcete-li zobrazit prezentaci na všech stránkách vložte do šablony:

    <?=slideshow('ADRESÁŘ', 'NASTAVENÍ');?>

Chcete-li zobrazit prezentaci na jedné stránce nebo v Newsboxu vložte na stránku:

    {{{slideshow('ADRESÁŘ', 'NASTAVENÍ')}}}

Tyto parametry mají následující význam:
- `ADRESÁŘ`:
  Cesta k adresáři relativní k adresáři images v CMSimple\_XH.
  Všechny JPEG, PNG a GIF obrázky uvnitř této složky budou použity pro prezentaci;
  there must be at least two images inside this folder.
  If there are also `*.webp` images with the same basename as a recognized image,
  this are used instead, if WebP is supported by the browser.
  All images should have the same aspect ratio.
- `NASTAVENÍ`:
  Jakákoli volba přepíše odpovídající hodnotu ve `Default` části konfigurace pluginu.
  Formát tohoto parametru je stejný jako "řetězec dotazu"
  (viz příklady níže).
  Možnosti mohou být uvedeny v libovolném pořadí.
  Pokud chcete použít výchozí nastavení, můžete tento parametr vynecháte.
  K dispozici jsou následující možnosti:
  - `order`:
    Pořadí obrázků:
    `fixed` (abecedně seřazeny; start od prvního obrázku),
    `sorted` (abecedně seřazeny; start od náhodného obrázku)
    nebo `random` (náhodné zobrazení).
  - `effect`:
    Druh přechodu: `fade`, `slide`, `curtain` nebo `random`.
  - `easing`:
    Efekt akcelerace: `linear`, `easeIn`, `easeOut` nebo `easeInOut`.
  - `delay`:
    Počáteční zpoždění v milisekundy než se spustí prezentace.
  - `pause`:
    - Doba trvání pauzy mezi přechody v milisekundy.
  - `duration`:
    Doba trvání přechodového efektu v milisekundy.

### Příklady

Chcete-li zobrazit obrázky z adresáře `userfiles/images/bannery/`
ve výchozím nastavením pluginu na každé stránce:

    <?=slideshow('bannery')?>

Způsob, chcete-li zobrazit snímky v posuvném pásu:

    {{{slideshow('slides/run/', 'effect=slide&amp;pause=0&amp;duration=2000')}}}

Chcete-li zobrazit obrázky klidně, pomalé vyblednutí slideshow:

    {{{slideshow('slides/slow/', 'effect=fade&amp;pause=5000&amp;duration=100')}}}

Chcete-li zobrazit odpovídající obrázky ve stylu knihy s listováním:

    {{{slideshow('slides/flip/', 'order=fixed&amp;effect=fade&amp;pause=100&amp;duration=100')}}}

## Omezení

Tyto prezentace mohou být přehrávány pouze,
pokud je JS povolen v prohlížeči návštěvníka.
Jinak ukáže pouze první obrázek.
Přechodové efekty jsou náročné na výkon CPU,
zejména pro velké obrázky.
Takže byste se měli omezit jen na několik
prezentací s malým nebo středním obrázky na stejné stránce,
aby se zabránilo trhání prezentace pro návštěvníky s malým výpočetním výkonem.

## Troubleshooting

Report bugs and ask for support either on
[Github](https://github.com/cmb69/slideshow_xh/issues)
or in the [CMSimple\_XH Forum](https://cmsimpleforum.com/).

## License

Slideshow\_XH je svobodný software: můžete jej šířit a/nebo modifikovat
podle podmínek GNU General Public License, vydané
Free Software Foundation, buď ve verzi 3 licence, nebo
(podle vašeho uvážení) kterékoli pozdější verze.

Slideshow\_XH je rozšiřován v naději, že bude užitečný,
ale BEZ JAKÉKOLIV ZÁRUKY; dokonce i bez předpokládané záruky
PRODEJNOSTI anebo VHODNOSTI PRO URČITÝ ÚČEL. viz
GNU General Public License pro více informací.

Měli by jste obdržet kopii GNU General Public License
spolu s Slideshow\_XH.  If not, see <https://www.gnu.org/licenses/>.

Copyright 2012-2021 Christoph M. Becker

Dánský překlad © 2012 Jens Maegard  
Český překlad © 2012-2013 Josef Němec  
Slovenský překlad © 2013 Dr. Martin Sereday  
Francouzský překlad © 2014 Patrick Varlet

## Credits

Slideshow\_XH was inspired by *Joe*.

The plugin icon is designed by [Mischa McLachlan](https://twitter.com/Zyote).
Many thanks for publishing this icon under a liberal license.

Many thanks to the community at the 
[CMSimple\_XH Forum](https://www.cmsimpleforum.com/)
for tips, suggestions and testing.
Particularly, many thanks to *Caravaner* for the prompt reporting of bugs,
and *olape* for suggesting the progressive WebP enhancement.

And last but not least many thanks to
[Peter Harteg](https://www.harteg.dk/), the “father” of CMSimple,
and all developers of [CMSimple\_XH](https://www.cmsimple-xh.org/)
without whom this amazing CMS would not exist.
