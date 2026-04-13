<?php echo $this->element('base/header') ?>
<?php echo $this->element('base/menu') ?>
<?php echo $this->element('base/flash'); ?>
<div class="top_back"></div>
<div class="profile">
    <div class="profile_header_wrap">
        <div class="profile_header">
            <?php echo $this->element('profile/header') ?>
        </div>
    </div>
    <div class="profile_content">
        <?php echo $this->fetch('content'); ?>
    </div>
</div>
<?php echo $this->element('base/footer') ?>
<?php //echo $this->element('sql_dump');?>

