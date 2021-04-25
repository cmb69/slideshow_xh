<?php
if (!isset($this)) {
    header('HTTP/1.0 404 Not Found');
    exit;
}
?>

<!-- Slideshow_XH info -->
<h1>Slideshow <?=$this->version()?></h1>
<h4><?=$this->text('synopsis')?></h4>
<pre>&lt;?=slideshow('<?=$this->text('synopsis_folder')?>', '<?=$this->text('synopsis_options')?>')?&gt;
{{{slideshow('<?=$this->text('synopsis_folder')?>', '<?=$this->text('synopsis_options')?>')}}}</pre>
<dl>
    <dt><?=$this->text('synopsis_folder')?>:</dt>
    <dd><?=$this->text('synopsis_folder_desc')?></dd>
    <dt><?=$this->text('synopsis_options')?>:</dt>
    <dd><?=$this->text('synopsis_options_desc')?>
    <dl>
        <dt>order</dt>
        <dd><?=$this->text('cf_default_order')?></dd>
        <dt>effect</dt>
        <dd><?=$this->text('cf_default_effect')?></dd>
        <dt>easing</dt>
        <dd><?=$this->text('cf_default_easing')?></dd>
        <dt>delay</dt>
        <dd><?=$this->text('cf_default_delay')?></dd>
        <dt>pause</dt>
        <dd><?=$this->text('cf_default_pause')?></dd>
        <dt>duration</dt>
        <dd><?=$this->text('cf_default_duration')?></dd>
    </dl></dd>
</dl>
<h4><?=$this->text('syscheck')?></h4>
<?php foreach ($this->checks as $check):?>
<p class="<?=$this->escape($check['class'])?>"><?=$this->escape($check['message'])?></p>
<?php endforeach?>
