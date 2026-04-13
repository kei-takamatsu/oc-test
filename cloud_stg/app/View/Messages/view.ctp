<?php echo $this->Html->css('mypage', null, array('inline' => false)) ?>

<?php //echo $this->element('message/menu', array('mode' => 'box'))?>

<h4><span class="el-icon-envelope"></span>
    <?php echo h($to_user['User']['nick_name']) ?>さんとのメッセージ
</h4>

<div class="clearfix">
    <div class="col-md-3">
        <div style="text-align: center;">
            <br>
            <div class="user_img">
                <a href="<?php echo $this->Html->url('/profile/'.$to_user['User']['id']) ?>">
                    <?php echo $this->User->get_user_img_md_from_user_100($to_user, 200) ?>
                </a><br><br>
            </div>
            <div class="user_name">
                <a href="<?php echo $this->Html->url('/profile/'.$to_user['User']['id']) ?>">
                    <?php echo h($to_user['User']['nick_name']) ?> さん
                </a><br>
            </div>
            <div class="address">
                <?php if(!empty($to_user['User']['address'])): ?>
                    <span class="el-icon-map-marker"></span>
                    <?php echo h($to_user['User']['address']) ?>
                <?php endif ?>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <?php if(!empty($messages)): ?>
            <?php foreach($messages as $idx => $m): ?>
                <div class="clearfix">
                    <div class="message">
                        <div class="clearfix">
                            <div class="pull-left">
                                <div class="user_info">
                                    <?php echo $this->User->get_user_img_md_from_user_id($m['Message']['from_id'], 20) ?>
                                    <?php if($m['Message']['from_id'] == $auth_user['User']['id']): ?>&nbsp;
                                        <?php echo h($auth_user['User']['nick_name']) ?>
                                    <?php else: ?>
                                        <?php echo h($to_user['User']['nick_name']) ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="pull-right">
                                <div class="message_datetime">
                                    <?php echo date('Y/m/d H:i', strtotime($m['Message']['created'])) ?>
                                </div>
                            </div>
                        </div>

                        <div class="message_content">
                            <?php echo nl2br(h($m['Message']['content'])) ?>
                        </div>

                        <div class="clearfix">
                            <div class="pull-right">
                                <a href="#reply_form" class="btn btn-primary btn-xs">返信</a>
                            </div>
                        </div>
                    </div>

                    <?php if($idx == 0): ?>
                        <div id="reply_form">
                            <?php echo $this->Form->create('Message', array('inputDefaults' => array('class' => 'form-control'))) ?>
                            <div class="form-group">
                                <?php echo $this->Form->input('content', array('label' => '返信メッセージ')) ?>
                            </div>
                            <div class="form-group clearfix">
                                <div class="col-xs-offset-2 col-xs-8" style="margin-top:10px;">
                                    <?php echo $this->Form->submit('送信', array('class' => 'btn btn-primary btn-block')) ?>
                                </div>
                            </div>
                            <?php echo $this->Form->end() ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach ?>
            <?php echo $this->element('base/pagination') ?>

        <?php else: ?>
            <br>
            <?php echo $this->Form->create('Message', array('inputDefaults' => array('class' => 'form-control'))) ?>
            <div class="form-group">
                <?php echo $this->Form->input('content', array('label' => 'メッセージ')) ?>
            </div>
            <div class="form-group clearfix">
                <div class="col-xs-offset-2 col-xs-8" style="margin-top:10px;">
                    <?php echo $this->Form->submit('送信', array('class' => 'btn btn-primary btn-block')) ?>
                </div>
            </div>
            <?php echo $this->Form->end() ?>
        <?php endif ?>
    </div>
</div>



