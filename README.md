# Slideshow\_XH

Slideshow\_XH facilitates to display all images
in a given folder as a slideshow.
The slideshows do not support any interaction of the visitor.
Multiple slideshows with different effects and timings are possible.

- [Requirements](#requirements)
- [Download](#download)
- [Installation](#installation)
- [Settings](#settings)
- [Usage](#usage)
  - [Examples](#examples)
- [Limitations](#limitations)
- [Troubleshooting](#troubleshooting)
- [License](#license)
- [Credits](#credits)

## Requirements

Slideshow_XH is a plugin for [CMSimple_XH](https://www.cmsimple-xh.org/).
It requires CMSimple_XH ≥ 1.7.0 and PHP ≥ 7.1.0.
Slideshow_XH also requires [Plib_XH](https://github.com/cmb69/plib_xh) ≥ 1.2;
if that is not already installed (see `Settings` → `Info`),
get the [lastest release](https://github.com/cmb69/plib_xh/releases/latest),
and install it.

## Download

The [lastest release](https://github.com/cmb69/slideshow_xh/releases/latest)
is available for download on Github.

## Installation

The installation is done as with many other CMSimple\_XH plugins.

1. Backup the data on your server.
1. Unzip the distribution on your computer.
1. Upload the whole directory `slideshow/` to your server
   into the `plugins/` directory of CMSimple\_XH.
1. Set write permissions for the subdirectories
   `config/` and `languages/`.
1. Navigate to `Plugins` → `Slideshow` in the back-end
   to check if all requirements are fulfilled.

## Settings

The configuration of the plugin is done as with many other CMSimple\_XH
plugins in the back-end of the Website.
Go to `Plugins` → `Slideshow`.

You can change the default settings of Slideshow\_XH under `Config`.
Hints for the options will be displayed
when hovering over the help icon with your mouse.

Localization is done under `Language`.
You can translate the character strings to your own language,
if there is no appropriate language file available,
or customize them according to your needs.

## Usage

To show a slideshow on all pages insert into the template:

    <?=slideshow('FOLDER', 'OPTIONS')?>

To show a slideshow on a single page or in a newsbox insert into the page:

    {{{slideshow('FOLDER', 'OPTIONS')}}}

The parameters have the following meaning:

- `FOLDER`:
  The path of a folder relative to the image folder of CMSimple_XH.
  All JPEG, PNG and GIF images inside this folder
  will be used for the slideshow;
  there must be at least two images inside this folder.
  If there are also `*.webp` and/or `*.avif` images with the same basename as a recognized image,
  these are used instead, if the image format is supported by the browser.
  All images should have the same aspect ratio.
- `OPTIONS`:
  Any given option will override the respective value
  in the `Default` section of the plugin configuration.
  The format of this parameter is the same as a “query string”
  (see the examples below).
  The options can be given in any order.
  If you want to stick with the defaults,
  you can omit this parameter.
  The following options are available:
  - `order`:
    The order of the images:
    `fixed` (alphabetically ordered; start with first image),
    `sorted` (alphabetically sorted; start with randomly chosen image)
    or `random` (randomly ordered).
  - `effect`:
    The kind of transition: `fade`, `slide`, `curtain` or `random`.
  - `easing`:
    The acceleration effect: `linear`, `easeIn`, `easeOut` or `easeInOut`.
  - `delay`:
    The initial delay in milliseconds until the slideshow starts.
  - `pause`:
    The duration of the pause between the transitions in milliseconds.
  - `duration`:
    The duration of the transition effect in milliseconds.

### Examples

To show the images inside of `userfiles/images/banners/`
with the default settings on every page:

    <?=slideshow('banners')?>

To show images in a treatmill fashion:

    {{{slideshow('slides/run/', 'effect=slide&amp;pause=0&amp;duration=2000')}}}

To show images as calm, slowly cross-fading slideshow:

    {{{slideshow('slides/slow/', 'effect=fade&amp;pause=5000&amp;duration=100')}}}

To show appropriate images in a flip-book style:

    {{{slideshow('slides/flip/', 'order=fixed&amp;effect=fade&amp;pause=100&amp;duration=100')}}}

## Limitations

The slideshows can only be played,
when JS is enabled in the browser of the visitors,
and they are using contemporary browsers,
Otherwise only the first image will be shown.
The transition effects are CPU-intensive,
particularly for large images,
so you should restrain yourself to only a few slideshows
with small or medium-sized images on the same page,
to avoid stuttering slideshows for visitors with little processing power.

## Troubleshooting

Report bugs and ask for support either on
[Github](https://github.com/cmb69/slideshow_xh/issues)
or in the [CMSimple\_XH Forum](https://cmsimpleforum.com/).

## License

Slideshow\_XH is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Slideshow\_XH is distributed in the hope that it will be useful,
but *without any warranty*; without even the implied warranty of
*merchantibility* or *fitness for a particular purpose*. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Slideshow\_XH.  If not, see <https://www.gnu.org/licenses/>.

Copyright © Christoph M. Becker

Danish translation © Jens Maegard<br>
Czech translation © Josef Němec<br>
Slovak translation © Dr. Martin Sereday<br>
French translation © Patrick Varlet

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
