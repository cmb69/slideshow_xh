<?php
if (!isset($this)) {
    header('HTTP/1.0 404 Not Found');
    exit;
}
?>

<!-- Slideshow_XH info -->
<h1>Slideshow <?php echo $version?></h1>
<h4><?php echo $tx['synopsis']?></h4>
<pre>&lt;php echo slideshow('<?php echo $tx['synopsis_folder']?>', '<?php echo
$tx['synopsis_options']?>');&gt;
{{{slideshow('<?php echo $tx['synopsis_folder']?>', '<?php echo
$tx['synopsis_options']?>');}}}</pre>
<dl>
    <dt><?php echo $tx['synopsis_folder']?>:</dt>
    <dd><?php echo $tx['synopsis_folder_desc']?></dd>
    <dt><?php echo $tx['synopsis_options']?>:</dt>
    <dd><?php echo $tx['synopsis_options_desc']?>
    <dl>
        <dt>order</dt>
        <dd><?php echo $tx['cf_default_order']?></dd>
        <dt>effect</dt>
        <dd><?php echo $tx['cf_default_effect']?></dd>
        <dt>easing</dt>
        <dd><?php echo $tx['cf_default_easing']?></dd>
        <dt>delay</dt>
        <dd><?php echo $tx['cf_default_delay']?></dd>
        <dt>pause</dt>
        <dd><?php echo $tx['cf_default_pause']?></dd>
        <dt>duration</dt>
        <dd><?php echo $tx['cf_default_duration']?></dd>
    </dl></dd>
</dl>
<h4><?php echo $tx['syscheck']?></h4>
<ul style="list-style: none">
<?php foreach ($checks as $check => $state):?>
    <li>
        <img src="<?php echo $images[$state]?>" alt="<?php echo
        $images[$state]?>" style="margin: 0; height: 1em; padding-right: 1em">
        <span><?php echo $check?></span>
    </li>
<?php endforeach?>
</ul>
