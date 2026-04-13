<?php if(empty($setting)){
    echo $this->Html->css('admin/fundee_login', null, array('inline' => false));
}else{
    echo $this->Html->css('login', null, array('inline' => false));
} ?>

<div class="login_box_wrap">
    <div class="login_box clearfix">
        <h3>アカウント登録</h3>
        <?php echo $this->Form->create('User', array('inputDefaults' => array('class' => 'form-control'))); ?>
        <div class="form-group clearfix">
            <div class="col-sm-offset-1 col-sm-10">
                <?php echo $this->Form->input('email', array('label' => 'メールアドレスを入力してください')); ?>
            </div>
        </div>

        <div class="form-group clearfix">
            <div class="col-sm-offset-2 col-sm-8">
                <?php echo $this->Form->submit('送信', array('class' => 'btn btn-success form-control')) ?>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>

        <hr>

        <div class="col-sm-offset-1 col-sm-10 clearfix">
            <?php if(!empty($facebook_login_url)): ?>
                <div class="clearfix" style="margin-bottom:30px;">
                    <?php echo $this->Html->link(__('<span class="el-icon-facebook"></span> Facebookで新規登録'), $facebook_login_url, array(
                            'escape' => false, 'class' => 'btn btn-primary btn-block'
                    )); ?>
                </div>
            <?php endif ?>

            <div class="clearfix">
                <a href="<?php echo $this->Html->url('/tw_login') ?>" class="btn btn-info btn-block">
                    <span class="el-icon-twitter"></span>
                    Twitterで新規登録
                </a>
            </div>
        </div>
    </div>
</div>

