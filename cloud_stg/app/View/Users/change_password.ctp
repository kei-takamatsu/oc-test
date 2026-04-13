<?php echo $this->Html->css('mypage', null, array('inline' => false)) ?>

<div class="sub_menu_wrap">
    <div class="sub_menu" onclick="location.href='<?php echo $this->Html->url(array(
            'controller' => 'users', 'action' => 'edit'
    )) ?>';">
        プロフィール編集
    </div>
    <div class="sub_menu active">
        パスワード変更
    </div>
</div>

<h4><span class="el-icon-user"></span> パスワード変更</h4>

<div class="content clearfix">

    <?php echo $this->Form->create('User', array('inputDefaults' => array('class' => 'form-control'))); ?>
    <div class="col-md-8 col-md-offset-2">
        <div class="form-group">
            <?php echo $this->Form->input('password', array('label' => 'パスワード')); ?>
        </div>
        <div class="form-group">
            <?php echo $this->Form->input('password2', array(
                    'type' => 'password', 'label' => 'パスワード（確認）'
            )); ?>
        </div>

        <div class="col-xs-8 col-xs-offset-2">
            <br>
            <?php echo $this->Form->submit(__('変更'), array('class' => 'btn btn-primary btn-block')); ?>
            <br>
        </div>
    </div>

    <?php echo $this->Form->end(); ?>
</div>



