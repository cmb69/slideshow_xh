<?php
if (!isset($this)) {
    header('HTTP/1.0 404 Not Found');
    exit;
}
?>

<!-- Slideshow_XH slideshow -->
<div class="slideshow" style="position: relative; width: 100%; overflow: hidden"
        data-effect="<?=$this->option('effect')?>" data-easing="<?=$this->option('easing')?>"
        data-delay="<?=$this->option('delay')?>" data-pause="<?=$this->option('pause')?>" data-duration="<?=$this->option('duration')?>">
<?php foreach ($this->imgs as $i => $img):?>
    <picture style="<?=$this->style($i)?>">
<?php   if ($img->hasWebp()):?>
        <source srcset="<?=$this->escape($img->getWebp())?>" type="image/webp">
<?php   endif?>
        <img src="<?=$this->escape($img->getFilename())?>" alt="<?=$this->escape($img->getName())?>">
    </picture>
<?php endforeach?>
</div>
