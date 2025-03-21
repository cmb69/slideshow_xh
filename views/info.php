<?php

use Plib\View;

if (!isset($this)) {http_response_code(403); exit;}

/**
 * @var View $this
 * @var string $version
 * @var list<array{class:string,message:string}> $checks
 */
?>

<!-- Slideshow_XH info -->
<h1>Slideshow <?=$this->esc($version)?></h1>
<h2><?=$this->text('synopsis')?></h2>
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
<h2><?=$this->text('syscheck')?></h2>
<?foreach ($checks as $check):?>
<p class="<?=$this->esc($check['class'])?>"><?=$this->esc($check['message'])?></p>
<?endforeach?>
