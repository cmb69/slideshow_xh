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
    <img src="<?=$this->escape($img->getFilename())?>" alt="<?=$this->escape($img->getName())?>" style="<?=$this->style($i)?>">
<?php endforeach?>
</div>
