<?php
if (!isset($this)) {
    header('HTTP/1.0 404 Not Found');
    exit;
}
?>

<!-- Slideshow_XH slideshow -->
<script type="module" src="<?=$this->esc($script)?>"></script>
<div class="slideshow" style="position: relative; width: 100%; overflow: hidden"
        data-effect="<?=$this->esc($opts['effect'])?>" data-easing="<?=$this->esc($opts['easing'])?>"
        data-delay="<?=$this->esc($opts['delay'])?>" data-pause="<?=$this->esc($opts['pause'])?>" data-duration="<?=$this->esc($opts['duration'])?>">
<?php foreach ($imgs as $i => $img):?>
    <picture style="<?=$this->esc($styles[$i])?>">
<?php   if ($img->hasWebp()):?>
        <source srcset="<?=$this->esc($img->getWebp())?>" type="image/webp">
<?php   endif?>
        <img src="<?=$this->esc($img->getFilename())?>" alt="<?=$this->esc($img->getName())?>" loading="<?=$this->esc($loading[$i])?>">
    </picture>
<?php endforeach?>
</div>
