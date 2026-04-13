<!--<br>-->
<!--<ul class="nav nav-tabs">-->
<!--	<li class="active">-->
<!--		--><?php //echo $this->Html->link( 'プロジェクト一覧', '/admin/projects' ) ?>
<!--	</li>-->
<!--	<li>-->
<!--		--><?php //echo $this->Html->link( 'プロジェクト追加', '/admin/projects/add' ) ?>
<!--	</li>-->
<!--</ul>-->

<h2><?php echo __('%s一覧', __('プロジェクト')); ?></h2>

<?php echo $this->Form->create(false, array(
        'type' => 'get', 'inputDefaults' => array(
                'label' => false, 'class' => 'form-control'
        )
)); ?>
<table class="search">
    <tr>
        <th>検索</th>
        <td>
            <?php echo $this->Form->input('search_id', array(
                    'placeholder' => 'プロジェクトID', 'type' => 'text', 'value' => ''
            )); ?>
        </td>
        <td>
            <?php echo $this->Form->submit(__('検索'), array(
                    'class' => 'btn btn-primary', 'style' => 'margin-left:10px;'
            )); ?>
        </td>
    </tr>
    <tr>
        <th style="padding-top:10px;">ソート</th>
        <td colspan="2" style="padding-top:10px;">
            <?php echo $this->Paginator->sort('id', 'ID', array('class' => 'btn btn-default')); ?>
            <?php echo $this->Paginator->sort('goal_amount', '目標金', array('class' => 'btn btn-default')); ?>
            <?php echo $this->Paginator->sort('collection_end_date', '終了日', array('class' => 'btn btn-default')); ?>
            <?php echo $this->Paginator->sort('collected_amount', '現在金', array('class' => 'btn btn-default')); ?>
        </td>
    </tr>
</table>
<?php echo $this->Form->end(); ?>

<?php foreach($projects as $project): ?>
    <div class="media">
        <div class="pull-left">
            <?php if($this->Label->link($project['Project']['pic'])): ?>
                <?php echo $this->Label->image($project['Project']['pic'], array(
                        'class' => 'media-object', 'width' => '102', 'height' => '64'
                )) ?>
            <?php else: ?>
                <div class="noimage"></div>
            <?php endif; ?>
        </div>
        <div class="media-body">
            <h4 class="media-heading">
                <?php echo h($project['Project']['id']) ?> -
                <?php echo h($project['Project']['project_name']); ?>
            </h4>

            <div class="col-md-8">
                目標：<?php echo $project['Project']['goal_amount'] ? number_format(h($project['Project']['goal_amount'])) : ''; ?>
                円<br>
                現在：<?php echo number_format(h($project['Project']['collected_amount'])); ?> 円<br>
                終了：<?php echo date('Y年m月d日', strtotime($project['Project']['collection_end_date'])); ?>
            </div>
            <div class="col-md-4 action">
                <?php echo $this->Html->link(__('編集'), array(
                        'action' => 'edit', $project['Project']['id']
                ), array('class' => 'btn btn-success btn-sm')); ?>
                　<?php echo $this->Form->postLink(__('削除'), array(
                        'action' => 'delete', $project['Project']['id']
                ), array('class' => 'btn btn-danger btn-sm'), __('削除しますか？')); ?>
                　
                <?php if($project['Project']['opened'] == 'no'){
                    echo $this->Form->postLink(__('公開'), array(
                            'action' => 'admin_change_open', $project['Project']['id'], '1'
                    ), array('class' => 'btn btn-warning btn-sm'), __('公開しますか？'));
                }else{
                    echo $this->Form->postLink(__('非公開'), array(
                            'action' => 'admin_change_open', $project['Project']['id'], '0'
                    ), array('class' => 'btn btn-warning btn-sm'), __('非公開にしますか？'));
                } ?>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<?php echo $this->element('base/pagination') ?>
