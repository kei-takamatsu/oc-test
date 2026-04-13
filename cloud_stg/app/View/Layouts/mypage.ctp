<?php echo $this->element('base/header') ?>
<?php echo $this->element('base/menu') ?>
<?php echo $this->element('base/flash'); ?>
<div class="mypage">
    <div class="mypage_header">
        <?php echo $this->element('mypage/header') ?>
    </div>
    <div class="mypage_content">
        <?php echo $this->fetch('content'); ?>
    </div>
</div>
<?php echo $this->element('base/footer') ?>
<?php //echo $this->element('sql_dump');?>

