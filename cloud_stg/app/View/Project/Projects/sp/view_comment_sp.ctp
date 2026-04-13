<?php echo $this->Html->css('sp/project_view', null, array('inline' => false)) ?>

<?php echo $this->element('project/sp/tab_header_back') ?>
<div class="project">
    <h3><span class="el-icon-comment"></span> コメント</h3>
    <?php if(!empty($comments)): ?>
        <br>
        <?php foreach($comments as $comment): ?>
            <div class="project_comment clearfix">
                <table>
                    <tr>
                        <td class="img_cell">
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
							<span class="date_str">
								<?php echo date('Y/m/d', strtotime($comment['Comment']['created'])); ?>
							</span>
                            <br>
                            <?php if($comment['User']['active']): ?>
                                <?php echo $this->Html->link(h($comment['User']['nick_name']), array(
                                        'controller' => 'users', 'action' => 'view', $comment['User']['id']
                                )); ?>
                            <?php else: ?>
                                退会したユーザ
                            <?php endif; ?>
                            <br>
                            <?php echo nl2br(h($comment['Comment']['comment'])); ?>
                        </td>
                    </tr>
                </table>
            </div>
            <hr>
        <?php endforeach; ?>

        <div style="padding:0 10px;">
            <?php echo $this->element('base/pagination') ?>
        </div>
    <?php else: ?>
        <div style="padding:20px 10px;">

            <p>コメントはまだありません。</p>
            <br>
        </div>
        <hr>
    <?php endif; ?>

    <?php if($auth_user): ?>
        <div class="comment_add">
            <h4>コメントの投稿</h4>
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
                    'div' => 'controls', 'class' => 'btn btn-primary btn-block'
            )); ?>
            <?php echo $this->Form->end(); ?>
        </div>
        <br>
    <?php endif; ?>
</div>
