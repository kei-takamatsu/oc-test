<div class="grid_wrap">
    <?php echo $this->element('project_box/project_box', compact('project')) ?>

    <div class="buttons clearfix">
        <button class="btn btn-block btn-primary" data-toggle="modal"
                data-target="#backed<?php echo $project['BackedProject']['id'] ?>">支援内容
        </button>
    </div>
</div>

<div class="modal fade modal-backed" id="backed<?php echo $project['BackedProject']['id'] ?>" tabindex="-1"
     role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">

                <dl>
                    <dt>￥ 支援金額</dt>
                    <dd>
                        <?php echo number_format($project['BackedProject']['invest_amount']); ?> 円

                    </dd>

                    <dt><span class="el-icon-comment"></span> 支援コメント</dt>
                    <dd>
                        <?php echo nl2br(h($project['BackedProject']['comment'])) ?>
                    </dd>

                    <dt><span class="el-icon-gift"></span> リターン</dt>
                    <dd>
                        <?php echo nl2br($project['BackingLevel']['return_amount']); ?>

                    </dd>
                </dl>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>