<?php
if (!isset($this)) {
    header('HTTP/1.0 404 Not Found');
    exit;
}
?>

<!-- Slideshow_XH info -->
<h1>Slideshow <?=$version?></h1>
<h4><?=$tx['synopsis']?></h4>
<pre>&lt;?=slideshow('<?=$tx['synopsis_folder']?>', '<?=$tx['synopsis_options']?>')?&gt;
{{{slideshow('<?=$tx['synopsis_folder']?>', '<?=$tx['synopsis_options']?>')}}}</pre>
<dl>
    <dt><?=$tx['synopsis_folder']?>:</dt>
    <dd><?=$tx['synopsis_folder_desc']?></dd>
    <dt><?=$tx['synopsis_options']?>:</dt>
    <dd><?=$tx['synopsis_options_desc']?>
    <dl>
        <dt>order</dt>
        <dd><?=$tx['cf_default_order']?></dd>
        <dt>effect</dt>
        <dd><?=$tx['cf_default_effect']?></dd>
        <dt>easing</dt>
        <dd><?=$tx['cf_default_easing']?></dd>
        <dt>delay</dt>
        <dd><?=$tx['cf_default_delay']?></dd>
        <dt>pause</dt>
        <dd><?=$tx['cf_default_pause']?></dd>
        <dt>duration</dt>
        <dd><?=$tx['cf_default_duration']?></dd>
    </dl></dd>
</dl>
<h4><?=$tx['syscheck']?></h4>
<?php foreach ($checks as $check):?>
<p class="<?=$check['class']?>"><?=$check['message']?></p>
<?php endforeach?>
