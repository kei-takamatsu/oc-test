<?php echo $this->element('admin/header') ?>
<?php //echo $this->element( 'admin/admin_menu' ) ?>
<?php echo $this->element('base/flash'); ?>
<div class="container admin">
    <?php echo $this->fetch('content'); ?>
</div>
