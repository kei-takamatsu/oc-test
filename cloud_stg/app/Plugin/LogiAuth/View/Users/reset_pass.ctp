<?php if(empty($setting)){
    echo $this->Html->css('admin/fundee_login', null, array('inline' => false));
}else{
    echo $this->Html->css('login', null, array('inline' => false));
} ?>

<div class="login_box_wrap">
    <div class="login_box clearfix">
        <h3>パスワード再設定</h3>

        <?php echo $this->Form->create('User', array(
                'inputDefaults' => array(
                        'class' => 'form-control', 'label' => false
                )
        )); ?>
        <div class="form-group">
            <?php echo $this->Form->input('password', array('placeholder' => 'パスワード')) ?>
        </div>
        <div class="form-group">
            <?php echo $this->Form->input('password2', array(
                    'type' => 'password', 'placeholder' => 'パスワード（確認）'
            )) ?>
        </div>

        <div class="form-group">
            <div class="col-md-6 col-md-offset-3" style="margin-bottom:20px;">
                <?php echo $this->Form->submit('送信', array('class' => 'btn btn-primary form-control')) ?>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>

