<?php echo $this->Html->css('project_view', null, array('inline' => false)) ?>

<h2 class="title"><span class="el-icon-comment"> コメント</h2>

<?php if(!empty($comments)): ?>
    <?php foreach($comments as $comment): ?>
        <div class="project_comment clearfix">
            <table style="width:100%;">
                <tr>
                    <td class="img_cell" style="width:68px;">
                        <?php if($comment['User']['active']): ?>
                            <a href="<?php echo $this->Html->url(array(
                                    'controller' => 'users', 'action' => 'view', $comment['User']['id']
                            )) ?>">
                                <?php echo $this->User->get_user_img_md($comment) ?>
                            </a>
                        <?php else: ?>
                            <?php echo $this->Html->image('/img/user.png', array('width' => 48)) ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="date_str pull-right">
                            <?php echo date('Y年m月d日　', strtotime($comment['Comment']['created'])); ?>
                        </div>
                        <div class="pull-left">
                            <?php if($comment['User']['active']): ?>
                                <?php echo $this->Html->link(h($comment['User']['nick_name']), array(
                                        'controller' => 'users', 'action' => 'view', $comment['User']['id']
                                )); ?>
                            <?php else: ?>
                                退会したユーザ
                            <?php endif; ?>
                            <br>

                            <?php echo nl2br(h($comment['Comment']['comment'])); ?>
                        </div>
                    </td>
                </tr>
            </table>
            <hr>
        </div>
    <?php endforeach; ?>
    <?php echo $this->element('base/pagination') ?>
<?php else: ?>
    <p>コメントはまだありません。</p>
    <br>
<?php endif; ?>

<?php if($auth_user): ?>
    <h3>コメントの投稿</h3>
    <?php echo $this->Form->create('Comment', array(
            'url' => array(
                    'controller' => 'comments', 'action' => 'add'
            ), 'inputDefaults' => array('class' => 'form-control')
    )); ?>

    <?php echo $this->Form->input('project_id', array(
            'type' => 'hidden', 'value' => $project['Project']['id']
    )); ?>
    <div class="form-group">
        <?php echo $this->Form->input('comment', array(
                'type' => 'textarea', 'label' => false, 'required' => 'required'
        )); ?>
    </div>
    <?php echo $this->Form->submit('投稿', array(
            'div' => 'controls', 'class' => 'btn btn-primary col-xs-4'
    )); ?>
    <?php echo $this->Form->end(); ?>
<?php endif; ?>
