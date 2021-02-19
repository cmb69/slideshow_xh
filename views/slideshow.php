<?php
if (!isset($this)) {
    header('HTTP/1.0 404 Not Found');
    exit;
}
?>

<!-- Slideshow_XH slideshow -->
<div class="slideshow" style="position: relative; width: 100%; overflow: hidden"
        data-id="<?=$id?>" data-effect="<?=$opts['effect']?>" data-easing="<?=$opts['easing']?>"
        data-delay="<?=$opts['delay']?>" data-pause="<?=$opts['pause']?>" data-duration="<?=$opts['duration']?>">
<?php foreach ($imgs as $i => $img):?>
    <img src="<?=$img->getFilename()?>" alt="<?=$img->getName()?>" style="<?=$imagestyle($i)?>">
<?php endforeach?>
</div>
