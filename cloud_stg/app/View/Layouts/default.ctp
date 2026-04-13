<?php echo $this->element('base/header') ?>
<?php echo $this->element('base/menu') ?>
<?php echo $this->element('base/flash'); ?>
<div class="top_back"></div>
<?php echo $this->fetch('content'); ?>
<?php echo $this->element('base/footer') ?>
<?php //echo $this->element('sql_dump');?>

