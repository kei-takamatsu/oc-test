<?php if(empty($setting)){
    echo $this->Html->css('admin/fundee_login', null, array('inline' => false));
}else{
    echo $this->Html->css('login', null, array('inline' => false));
} ?>

<div class="login_box_wrap">
    <div class="login_box clearfix">
        <h3>新規登録</h3>
        <?php echo $this->Form->create('User', array(
                'inputDefaults' => array(
                        'label' => false, 'div' => false, 'class' => 'form-control'
                )
        )) ?>
        <div class="form-group">
            <?php echo $this->Form->input('nick_name', array('placeholder' => 'ニックネーム')); ?>
        </div>
        <div class="form-group">
            <?php echo $this->Form->input('password', array(
                    'placeholder' => 'パスワード', 'value' => ''
            )) ?>
        </div>
        <!--		<div class="form-group">-->
        <!--			--><?php //echo $this->Form->input('password2',array('placeholder' => 'パスワード（確認）', 'value' => '', 'type' => 'password'))?>
        <!--		</div>-->
        <div class="form-group">
            <div class="col-xs-offset-3 col-xs-6" style="margin-bottom:20px;">
                <?php echo $this->Form->submit('登録', array('class' => 'btn btn-success btn-block')) ?>
            </div>
        </div>
        <?php echo $this->Form->end() ?>
    </div>
</div>

