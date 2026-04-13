<?php if(empty($setting)){
    echo $this->Html->css('admin/fundee_login', null, array('inline' => false));
}else{
    echo $this->Html->css('login', null, array('inline' => false));
} ?>

<!--
<div class="login_box_wrap">
    <div class="login_box clearfix">
        <h3>ログイン</h3>
        <?php echo $this->Form->create('User', array(
                'inputDefaults' => array(
                        'label' => false, 'div' => false, 'class' => 'form-control'
                )
        )) ?>
        <div class="form-group clearfix">
            <div class="col-sm-offset-1 col-sm-10">
                <?php echo $this->Form->input('email', array(
                        'placeholder' => 'メールアドレス', 'value' => ''
                )) ?>
            </div>
        </div>
        <div class="form-group clearfix">
            <div class="col-sm-offset-1 col-sm-10">
                <?php echo $this->Form->input('password', array(
                        'placeholder' => 'パスワード', 'value' => ''
                )) ?>
            </div>
        </div>
        <div class="form-group clearfix">
            <div class="col-sm-offset-2 col-sm-8 clearfix">
                <?php echo $this->Form->submit('ログイン', array('class' => 'btn btn-success form-control')) ?>
            </div>
        </div>
        <p class="forgot_pass">
            <?php echo $this->Html->link('パスワードを忘れた方', '/forgot_pass') ?><br>
            <?php echo $this->Html->link('まだアカウントをお持ちでない方', '/mail_auth') ?>
        </p>
        <?php echo $this->Form->end() ?>

        <hr>

        <div class="col-sm-offset-1 col-sm-10 clearfix">

            <?php if(!empty($facebook_login_url)): ?>
                <div class="clearfix" style="margin-bottom:30px;">
                    <?php echo $this->Html->link(__('<span class="el-icon-facebook"></span> Facebookでログイン'), $facebook_login_url, array(
                            'escape' => false, 'class' => 'btn btn-primary btn-block'
                    )); ?>
                </div>
            <?php endif ?>

            <div class="clearfix">
                <a href="<?php echo $this->Html->url('/tw_login') ?>" class="btn btn-info btn-block">
                    <span class="el-icon-twitter"></span>
                    Twitterでログイン
                </a>
            </div>
        </div>
    </div>
</div>
-->

        <section class="login_wrap">
            <div class="login_inner">
                <div class="login_box">
                    <h2 class="login_ttl">
                        ログイン
                    </h2>
        <?php echo $this->Form->create('User', array(
                'inputDefaults' => array(
                        'label' => false, 'div' => false, 'class' => 'login_inner_form'
                )
        )) ?>
<!--                    <form class="login_inner_form" action="index_login.php" accept-charset="UTF-8" method="post">-->
                        <div class="form_group_1">
                <?php echo $this->Form->input('email', array(
                        'placeholder' => 'メールアドレス', 'value' => '', 'class' => 'input_text01'
                )) ?>
<!--                            <input class="input_text01" tabindex="1" autofocus="autofocus" placeholder="メールアドレス" type="text" name="">-->
                        </div>
                        <div class="form_group_1">
                                            <?php echo $this->Form->input('password', array(
                        'placeholder' => 'パスワード', 'value' => '', 'class' => 'input_text01'
                )) ?>
<!--                            <input class="input_text01" tabindex="1" autofocus="autofocus" placeholder="パスワード" type="text" name="">-->
                        </div>
                        <div class="form_group_1">
                            <label class="retain" for="user_retain">
                                <input tabindex="3" class="retain_check" type="checkbox" value="1" name="" id="user_retain">ログイン状態を保持する
                            </label>
                        </div>
                        <div class="submit_box">
                            <input type="submit" name="" value="ログイン" class="submit_btn_1" data-ci="login-btn" data-disable-with="ログイン">
                        </div>
<!--                    </form>-->
        <?php echo $this->Form->end() ?>
                    <div class="form_group_1">
                        <a href="<?php echo $this->Html->url('/forgot_pass') ?>" class="pass_resetting_link">
                            パスワードを忘れた方はこちら
                        </a>
                    </div>
                    <div class="or_title"><span>OR</span></div>
                    <div class="sns_login_wrap">
            <?php if(!empty($facebook_login_url)): ?>
                        <?php echo $this->Html->link(__('<span>Facebookアカウントでログイン</span>'), $facebook_login_url, array(
                            'escape' => false, 'class' => 'facebook_login'
                    )); ?>
            <?php endif ?>

                        <a href="<?php echo $this->Html->url('/tw_login') ?>" class="twitter_login">
                            <span>Twitterアカウントでログイン</span>
                        </a>
                    </div>
                    <div class="register_lead">
                        <div class="rl_ttl">
                            会員登録がお済みでない方はこちら
                        </div>
                        <a href="<?php echo $this->Html->url('/mail_auth') ?>" class="rl_btn">
                            新規会員登録
                        </a>
                    </div>
                </div>
            </div>
        </section>


