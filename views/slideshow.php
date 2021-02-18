<?php
if (!isset($this)) {
    header('HTTP/1.0 404 Not Found');
    exit;
}
?>

<!-- Slidshow_XH slideshow -->
<div id="<?=$id?>" class="slideshow" style="position: relative; width: 100%; overflow: hidden">
<?php foreach ($imgs as $i => $img):?>
<?php
if ($i === 0) {
    $style = 'position: static; display: block; z-index: 1; width: 100%';
} else {
    $style = 'position: absolute; display: none; width: 100%';
}
?>
    <img src="<?=$img->getFilename()?>" alt="<?=$img->getName()?>" style="<?=$style?>">
<?php endforeach?>
</div>
