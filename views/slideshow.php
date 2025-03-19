<?php

use Plib\View;
use Slideshow\Model\Image;

if (!isset($this)) {http_response_code(403); exit;}

/**
 * @var View $this
 * @var string $script
 * @var array<string,string> $opts
 * @var list<Image> $imgs
 * @var list<string> $styles
 * @var list<string> $loading
 */
?>

<!-- Slideshow_XH slideshow -->
<script type="module" src="<?=$this->esc($script)?>"></script>
<div class="slideshow" style="position: relative; width: 100%; overflow: hidden"
     data-effect="<?=$this->esc($opts['effect'])?>" data-easing="<?=$this->esc($opts['easing'])?>"
     data-delay="<?=$this->esc($opts['delay'])?>" data-pause="<?=$this->esc($opts['pause'])?>" data-duration="<?=$this->esc($opts['duration'])?>">
<?foreach ($imgs as $i => $img):?>
  <picture style="<?=$this->esc($styles[$i])?>">
<?  if ($img->hasAvif()):?>
    <source srcset="<?=$this->esc($img->getAvif())?>" type="image/avif">
<?  endif?>
<?  if ($img->hasWebp()):?>
    <source srcset="<?=$this->esc($img->getWebp())?>" type="image/webp">
<?  endif?>
    <img src="<?=$this->esc($img->getFilename())?>" alt="<?=$this->esc($img->getName())?>" loading="<?=$this->esc($loading[$i])?>">
  </picture>
<?endforeach?>
</div>
