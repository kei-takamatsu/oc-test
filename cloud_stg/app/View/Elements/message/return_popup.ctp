<div class="modal fade modal-backed" id="backed<?php echo $backer['BackedProject']['id'] ?>" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">

                <?php foreach($backer['return_summary'] as $b_id => $return): ?>
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