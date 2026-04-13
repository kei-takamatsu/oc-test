<?php echo $this->Html->css('mypage', null, array('inline' => false)) ?>

<?php echo $this->element('message/menu', array('mode' => 'send')) ?>

<h4><span class="el-icon-envelope"></span> メッセージを送る</h4>

<a href="#" onclick="history.back(); return false;">&lt; 戻る</a>

<div class="container">
    <div class="clearfix">
        <div class="col-md-6">
            <h3>
                <a href="<?php echo $this->Html->url(array(
                        'controller' => 'projects', 'action' => 'view', $project['Project']['id']
                )) ?>">
                    <?php echo h($project['Project']['project_name']) ?>
                </a>
            </h3>
        </div>
        <div class="col-md-6">
            <button class="btn btn-primary" data-toggle="modal"
                    data-target="#backed<?php echo $backer['BackedProject']['id'] ?>">リターン内容
            </button>
        </div>
    </div>

    送信先：
    <?php if($mode == 'backer'): //ログインユーザが支援者?>
        <?php echo $this->User->get_user_img_md($owner) ?>
        <?php echo h($owner['User']['nick_name']) ?>
        （プロジェクトオーナー）
    <?php else: //ログインユーザはオーナー?>
        <?php echo $this->User->get_user_img_md($backer) ?>
        <?php echo h($backer['User']['nick_name']) ?>
        （支援者）
    <?php endif; ?>

    <?php echo $this->Form->create('MessageContent', array('inputDefaults' => array('class' => 'form-control'))) ?>
    <div class="form-group">
        <?php echo $this->Form->input('content', array('label' => 'メッセージ')) ?>
    </div>
    <div class="form-group clearfix" style="margin-top:20px;">
        <div class="col-xs-offset-2 col-xs-8">
            <?php echo $this->Form->submit('送信', array('class' => 'btn btn-primary btn-block')) ?>
        </div>
    </div>
    <?php echo $this->Form->end() ?>
</div>

<?php echo $this->element('message/return_popup') ?>

