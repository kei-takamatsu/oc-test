<?php echo $this->Html->css('mypage', null, array('inline' => false)) ?>

<h4><span class="el-icon-comment"></span> コメント</h4>

<div class="container">
    <?php if(!empty($comments)): ?>
        <?php foreach($comments as $comment): ?>
            <div class="mypage_comment clearfix">
                <div class="col-xs-10">
                    <div>
                        <?php echo $this->Html->link(h($comment['Project']['project_name']), array(
                                'controller' => 'projects', 'action' => 'view', $comment['Project']['id']
                        )); ?>
                    </div>
                    <div class="date">
                        <?php echo date('Y年m月d日　', strtotime($comment['Comment']['created'])); ?>
                    </div>
                    <div>
                        <?php echo $this->Text->truncate(nl2br(h($comment['Comment']['comment'])), 300, array(
                                'ending' => '...', 'exact' => false, 'html' => true
                        )); ?>
                    </div>
                </div>

                <div class="col-xs-2">
                    <?php echo $this->Form->postLink(__('削除'), array(
                            'action' => 'delete', $comment['Comment']['id']
                    ), array('class' => 'btn btn-danger btn-sm'), __('コメントを削除しますか?', $comment['Comment']['id']))
                    ?>
                </div>
            </div>
        <?php endforeach; ?>
        <?php echo $this->element('base/pagination') ?>
    <?php else: ?>
        <p>コメントはまだありません。</p>
    <?php endif; ?>
</div>





