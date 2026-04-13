<?php echo $this->Html->css('login', null, array('inline' => false)) ?>

<div class="login_box_wrap">
    <div class="login_box clearfix">

        <h3>ニックネームとパスワードを入力してください</h3>

        <?php echo $this->Form->create('User', array('inputDefaults' => array('class' => 'form-control'))); ?>

        <div class="form-group">
            <?php echo $this->Form->input('nick_name', array('label' => 'ニックネーム')); ?>
        </div>

        <div class="form-group">
            <?php echo $this->Form->input('password', array('label' => 'パスワード')); ?>
        </div>

        <!--<div class="form-group">-->
        <!--	--><?php //echo $this->Form->input( 'password2', array('label' => 'パスワード（確認）', 'type' => 'password') ); ?>
        <!--</div>-->

        <div class="form-group">
            <div class="col-xs-8 col-xs-offset-2">
                <?php echo $this->Form->submit('登録', array('class' => 'btn btn-primary btn-block')); ?>
            </div>
        </div>

        <?php echo $this->Form->end(); ?>

    </div>
</div>
