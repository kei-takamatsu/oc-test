<?php echo $this->Html->css('mypage', null, array('inline' => false)) ?>
<?php echo $this->Html->css('grid', null, array('inline' => false)) ?>
<?php echo $this->Html->script('grid_position', array('inline' => false)) ?>

<?php echo $this->element('message/menu', array('mode' => 'send')) ?>

    <h4><span class="el-icon-envelope"></span> メッセージを送る</h4>

<?php echo $this->element('message/send_menu', array('mode' => 'backed')) ?>

    <br>
<?php if(!empty($projects)): ?>
    <div id="grid_container" class="clearfix">
        <?php foreach($projects as $project): ?>
            <?php echo $this->element('project_box/project_box_for_message_backed', compact('project')) ?>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="container">
        <p>まだ支援して成功したプロジェクトはありません</p>
    </div>
<?php endif; ?>


<?php $this->start('script') ?>
    <script>
        $(document).ready(function () {
            grid_position();
        });
    </script>
<?php $this->end() ?>