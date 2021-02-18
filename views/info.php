<?php
if (!isset($this)) {
    header('HTTP/1.0 404 Not Found');
    exit;
}
?>

<!-- Slideshow_XH info -->
<h1>Slideshow <?=$version?></h1>
<h4><?=$tx['synopsis']?></h4>
<pre>&lt;=slideshow('<?=$tx['synopsis_folder']?>', '<?php echo
$tx['synopsis_options']?>');&gt;
{{{slideshow('<?=$tx['synopsis_folder']?>', '<?php echo
$tx['synopsis_options']?>');}}}</pre>
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
<ul style="list-style: none">
<?php foreach ($checks as $check => $state):?>
    <li>
        <img src="<?=$images[$state]?>" alt="<?php echo
        $images[$state]?>" style="margin: 0; height: 1em; padding-right: 1em">
        <span><?=$check?></span>
    </li>
<?php endforeach?>
</ul>
