<?php echo $this->Html->css('login', null, array('inline' => false)) ?>

<div class="login_box_wrap">
    <div class="login_box clearfix">

        <h3>メールアドレスとパスワードを入力してください</h3>
        <?php echo $this->Form->create('User', array(
                'inputDefaults' => array(
                        'label' => false, 'class' => 'form-control'
                )
        )); ?>

        <div class="form-group">
            <?php echo $this->Form->input('email', array('label' => 'メールアドレス')); ?>
        </div>

        <div class="form-group">
            <?php echo $this->Form->input('password', array('label' => 'パスワード')); ?>
        </div>

        <!--<div class="form-group">-->
        <!--	--><?php //echo $this->Form->input( 'password2', array(
        //		'type' => 'password', 'label' => 'パスワード（確認）'
        //	) ); ?>
        <!--</div>-->

        <div class="form-group">
            <div class="col-md-6 col-md-offset-3" style="margin-bottom:20px;">
                <?php echo $this->Form->submit('送信', array('class' => 'btn btn-primary form-control')) ?>
            </div>
        </div>

        <?php echo $this->Form->end(); ?>
    </div>
</div>
