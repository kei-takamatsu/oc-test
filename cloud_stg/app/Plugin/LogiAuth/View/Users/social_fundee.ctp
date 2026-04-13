<?php echo $this->Html->css('admin/setting', null, array('inline' => false)) ?>

<?php echo $this->element('admin/config_menu', array('mode' => 'account')) ?>

<?php echo $this->element('admin/user_menu', array('mode' => 'social')) ?>

<div class="container social">
    <?php if($user['User']['twitter_id'] == ''): ?>
        <a href="<?php echo $this->Html->url('/tw_login') ?>" class="btn btn-info btn-block btn-lg">
            <span class="el-icon-twitter"></span>
            Twitterと連携する
        </a>
    <?php else: ?>
        <?php echo $this->Form->postLink(__('<span class="el-icon-twitter"></span> twitterアカウントを解除する'), '/deactive/unlinkTwitter', array(
                'escape' => false, 'class' => 'btn btn-info btn-lg btn-block'
        ), __('Twitterとの連携を解除しますか？')); ?>
    <?php endif; ?>

    <?php if($user['User']['facebook_id'] == ''): ?>
        <?php echo $this->Html->link(__('<span class="el-icon-facebook"></span> Facebookと連携する'), $facebook_login_url, array(
                'escape' => false, 'class' => 'btn btn-primary btn-lg btn-block'
        )); ?>
    <?php else: ?>
        <?php echo $this->Form->postLink(__('<span class="el-icon-facebook"></span> Facebookアカウントを解除する'), '/deactive/unlinkFacebook', array(
                'escape' => false, 'class' => 'btn btn-primary btn-lg btn-block'
        ), __('Facebookとの連携を解除しますか？')); ?>
    <?php endif; ?>
</div>



