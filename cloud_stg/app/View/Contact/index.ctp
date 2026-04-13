<?php if(empty($setting)){
    echo $this->Html->css('admin/fundee_login', null, array('inline' => false));
}else{
    echo $this->Html->css('login', null, array('inline' => false));
} ?>

<div class="login_box_wrap">
    <div class="login_box">
        <h3>お問合わせ</h3>

        <?php echo $this->Form->create('Contact', array(
                'inputDefaults' => array(
                        'div' => false, 'class' => 'form-control'
                )
        )) ?>
        <div class="clearfix">
            <div class="form-group">
                <?php echo $this->Form->input('name', array('label' => 'お名前')) ?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('mail', array('label' => 'メールアドレス')) ?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('content', array(
                        'type' => 'textarea', 'label' => '内容'
                )) ?>
            </div>

            <div class="form-group">
                <div class="col-xs-offset-1 col-xs-10">
                    <?php echo $this->Form->submit('送信', array('class' => 'btn btn-success btn-block')) ?>
                </div>
            </div>
        </div>

        <?php echo $this->Form->end() ?>
    </div>
</div>

