<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="ja" xml:lang="ja" dir="ltr"
>
<head>
    <meta charset="utf-8">
    <title>
        <?php echo h($setting['site_name']) ?> - ただいまメンテナンス中です
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo $this->Html->meta('icon'); ?>
    <?php echo $this->Html->css('/bootstrap/css/bootstrap.min')."\n" ?>
    <?php echo $this->Html->css('error')."\n" ?>
</head>
<body>
<a href="<?php echo $this->Html->url('/') ?>">
    <p style="text-align:center; padding-top:30px;">
        <?php
        if(!empty($setting['logo'])){
            echo $this->Label->image($setting['logo'], array('width' => '200'));
        }else{
            echo h($setting['site_name']);
        }
        ?>
    </p>
</a>

<?php echo $this->fetch('content'); ?>
</body>
</html>