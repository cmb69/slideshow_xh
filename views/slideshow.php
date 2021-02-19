<?php
if (!isset($this)) {
    header('HTTP/1.0 404 Not Found');
    exit;
}
?>

<!-- Slideshow_XH slideshow -->
<div id="<?=$id?>" class="slideshow" style="position: relative; width: 100%; overflow: hidden">
<?php foreach ($imgs as $i => $img):?>
    <img src="<?=$img->getFilename()?>" alt="<?=$img->getName()?>" style="<?=$imagestyle($i)?>">
<?php endforeach?>
</div>
