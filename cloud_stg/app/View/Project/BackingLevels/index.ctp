<?php echo $this->Html->css('pay', null, array('inline' => false)) ?>

<?php echo $this->element('pay/pay_header') ?>

<div class="pay index">

    <div class="project_return project_content">
        <h3>リターンを選択してください！</h3>

        <div class="backing_levels">
            <?php foreach($backingLevels as $backingLevel): ?>
                <div class="backing_level index"
                     onclick="location.href='<?php echo $this->Html->url(array(
                             'controller' => 'backed_projects', 'action' => 'add', $backingLevel['BackingLevel']['id'],
                             $project['Project']['id']
                     )) ?>'">
                    <div class="clearfix">
                        <div class="col-xs-6">
                            <p class="return_price">
                                <?php echo number_format($backingLevel['BackingLevel']['invest_amount']); ?> 円
                            </p>
                        </div>
                        <div class="col-xs-6" style="text-align:right;">
                            <?php if(!empty($backingLevel['BackingLevel']['max_count'])): ?>
                                <p>
                                    <?php echo $this->Project->get_zan_back_label($backingLevel['BackingLevel']) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="clearfix">
                        <div class="col-xs-12">
                            <div class="clearfix">
                                <div style="float:left; padding-right:30px;">
                                    <p>
                                        <span class="el-icon-group"></span>
                                        支援者：<?php echo number_format($backingLevel['BackingLevel']['now_count']) ?>人
                                    </p>
                                </div>
                                <div style="float:left;">
                                    <p>
                                        <?php $delivery = Configure::read('DELIVERY') ?>
                                        <span class="el-icon-gift"></span>
                                        配送方法：<?php echo $delivery[h($backingLevel['BackingLevel']['delivery'])] ?>
                                    </p>
                                </div>
                            </div>

                            <?php echo nl2br($backingLevel['BackingLevel']['return_amount']); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
