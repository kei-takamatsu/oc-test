<?php echo $this->Html->css('mypage', null, array('inline' => false)) ?>
<?php echo $this->Html->script('client_resize', array('inline' => false)) ?>

<?php echo $this->element('mypage/mypage_project_menu', array('mode' => 'report')) ?>

<h4><span class="el-icon-th"></span> 活動報告の編集</h4>

<div class="content container">
    <?php echo $this->element('mypage/report_edit_menu', array('mode' => 'base')) ?>

    <?php echo $this->Form->create('Report', array(
            'type' => 'file', 'inputDefaults' => array('class' => 'form-control')
    )); ?>

    <div class="form-group">
        <?php echo $this->Form->input('title', array('label' => 'タイトル')) ?>
    </div>

    <div class="form-group">
        <?php echo $this->Form->input('thumbnail', array(
                'type' => 'file', 'class' => 'client_resize', 'label' => 'サムネイル画像',
                'onchange' => "client_resize($(this), event, 750, 500, 'preview_thumbnail', 'thumbnail');"
        )); ?>
        <div id="preview_thumbnail" style="max-width:400px; margin:10px auto; ">
            <?php if($this->Label->url($report['Report']['thumbnail'])){
                echo $this->Label->image($report['Report']['thumbnail'], array('class' => 'img-responsive'));
            } ?>
        </div>
    </div>

    <div class="form-group clearfix">
        <div class="col-xs-offset-2 col-xs-8" style="margin-top:10px;">
            <input type="submit"
                   onclick="save_form_data($(this), event, '<?php echo $this->Html->url(array(
                           'action' => 'edit', $report['Report']['id']
                   )) ?>'); return false;"
                   class="btn btn-primary btn-block" value="登録">
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</div>





