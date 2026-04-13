<?php echo $this->Html->css('mypage', null, array('inline' => false)) ?>

<?php echo $this->element('message/menu', array('mode' => 'send')) ?>

<h4><span class="el-icon-envelope"></span> メッセージを送る</h4>

<?php echo $this->element('message/send_menu', array('mode' => 'created')) ?>

<br>
<div class="container">
    <h3><?php echo h($project['Project']['project_name']) ?>への支援者一覧</h3>

    <?php foreach($backers as $b): ?>
        <div class="backer">
            <a href="<?php echo $this->Html->url(array(
                    'controller' => 'users', 'action' => 'view', $b['User']['id']
            )) ?>">
                <?php echo $this->User->get_user_img_md($b, 48) ?>
                <?php echo h($b['User']['nick_name']) ?>
            </a>
            <button class="btn btn-info" data-toggle="modal"
                    data-target="#backed<?php echo $b['BackedProject']['id'] ?>">リターン内容
            </button>
            <?php if($b['User']['id'] != $auth_user['User']['id']): ?>
                <button class="btn btn-primary"
                        onclick="location.href='<?php echo $this->Html->url(array(
                                'action' => 'send', $project['Project']['id'], $b['User']['id']
                        )) ?>'">送る
                </button>
            <?php endif ?>
        </div>

        <div class="modal fade modal-backed" id="backed<?php echo $b['BackedProject']['id'] ?>" tabindex="-1"
             role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">

                        <?php foreach($b['return_summary'] as $b_id => $return): ?>
                            <dl>
                                <dt>支援回数</dt>
                                <dd>
                                    計 <?php echo number_format($return['count']); ?> 回
                                </dd>

                                <dt><span class="el-icon-gift"></span> リターン</dt>
                                <dd>
                                    <?php echo nl2br(h($return['return_amount'])); ?>
                                </dd>
                            </dl>
                        <?php endforeach ?>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

    <?php endforeach ?>
</div>
