<div class="grid_wrap">
    <?php echo $this->element('project_box/project_box', compact('project')) ?>

    <div class="buttons clearfix">
        <?php if($project['User']['id'] == $auth_user['User']['id']): ?>
            <button class="btn btn-block btn-info" data-toggle="modal"
                    data-target="#backed<?php echo $project['BackedProject']['id'] ?>">リターン内容
            </button>
        <?php else: ?>
            <div class="left">
                <button class="btn btn-block btn-primary"
                        onclick="location.href='<?php echo $this->Html->url(array(
                                'action' => 'send', $project['Project']['id'], $auth_user['User']['id']
                        )) ?>'">送る
                </button>
            </div>
            <div class="right">
                <button class="btn btn-block btn-info" data-toggle="modal"
                        data-target="#backed<?php echo $project['BackedProject']['id'] ?>">リターン内容
                </button>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade modal-backed" id="backed<?php echo $project['BackedProject']['id'] ?>" tabindex="-1"
     role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">

                <?php foreach($project['return_summary'] as $b_id => $return): ?>
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