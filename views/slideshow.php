<?php

use Plib\View;

if (!isset($this)) {http_response_code(403); exit;}

/**
 * @var View $this
 * @var string $script
 * @var list<object{filename:string,name:string,webp:string,avif:string,style:string,loading:string}> $images
 * @var string $effect
 * @var string $easing
 * @var string $delay
 * @var string $pause
 * @var string $duration
 */
?>

<!-- Slideshow_XH slideshow -->
<script type="module" src="<?=$this->esc($script)?>"></script>
<div class="slideshow" style="position: relative; width: 100%; overflow: hidden"
     data-effect="<?=$this->esc($effect)?>" data-easing="<?=$this->esc($easing)?>"
     data-delay="<?=$this->esc($delay)?>" data-pause="<?=$this->esc($pause)?>" data-duration="<?=$this->esc($duration)?>">
<?foreach ($images as $image):?>
  <picture style="<?=$this->esc($image->style)?>">
<?  if ($image->avif):?>
    <source srcset="<?=$this->esc($image->avif)?>" type="image/avif">
<?  endif?>
<?  if ($image->webp):?>
    <source srcset="<?=$this->esc($image->webp)?>" type="image/webp">
<?  endif?>
    <img src="<?=$this->esc($image->filename)?>" alt="<?=$this->esc($image->name)?>" loading="<?=$this->esc($image->loading)?>">
  </picture>
<?endforeach?>
</div>
