<?php echo $this->fetch('content')."\n"; ?>

<?php if(!empty($setting['mail_signature'])): ?>
    <?php echo h($setting['mail_signature']) ?>
<?php else: ?>
    --------------------------------------------
    <?php echo $setting['site_name'], "\n" ?>
    <?php echo $setting['site_url'], "\n" ?>
<?php endif ?>






