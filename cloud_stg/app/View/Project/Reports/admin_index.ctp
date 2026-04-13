<h2><?php echo __('経過報告一覧'); ?></h2>

<?php foreach($progrssReports as $progrssReport): ?>
    <div class="clearfix">
        <h4 class="media-heading">
            <?php echo date('Y年m月d日', strtotime(h($progrssReport['ProgrssReport']['modified']))); ?>
            -
            <?php echo $this->Html->link($progrssReport['Project']['project_name'], '/projects/view/'
                                                                                    .$progrssReport['Project']['id']); ?>
        </h4>
        <div class="col-md-8">
            <?php echo $this->Text->truncate($progrssReport['ProgrssReport']['report'], 50, array('html' => true)); ?>
        </div>
        <div class="col-md-4 action">
            <?php echo $this->Html->link(__('編集'), array(
                    'action' => 'edit', $progrssReport['ProgrssReport']['id']
            ), array('class' => 'btn btn-success btn-sm')); ?>
            <?php echo $this->Form->postLink(__('削除'), array(
                    'action' => 'delete', $progrssReport['ProgrssReport']['id']
            ), array('class' => 'btn btn-danger btn-sm'), __('削除しますか？')); ?>
        </div>
    </div><br>
<?php endforeach; ?>

<?php echo $this->element('base/pagination'); ?>
