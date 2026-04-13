<?php if(empty($setting)){
    echo $this->Html->css('admin/fundee_login', null, array('inline' => false));
}else{
    echo $this->Html->css('login', null, array('inline' => false));
} ?>

<div class="login_box_wrap">
    <div class="login_box clearfix">
        <h3>アカウントロック解除</h3>

        <?php echo $this->Form->create('User', array('inputDefaults' => array('class' => 'form-control'))); ?>
        <div class="col-sm-offset-1 col-sm-10">
            <div class="form-group">
                <?php echo $this->Form->input('email', array(
                        'placeholder' => 'メールアドレス', 'label' => false
                )); ?>
            </div>

            <div class="form-group">
                <div class="col-xs-10 col-xs-offset-1">
                    <?php echo $this->Form->submit('送信', array('class' => 'btn btn-success form-control')) ?>
                </div>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>



