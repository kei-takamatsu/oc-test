<?php echo $this->Html->css('search', null, array('inline' => false)) ?>
<?php echo $this->Html->css('grid', null, array('inline' => false)) ?>
<?php echo $this->Html->script('grid_position', array('inline' => false)) ?>

<?php if($smart_phone): ?>
    <?php echo $this->Html->css('sp/grid_sp', null, array('inline' => false)) ?>
<?php endif ?>

<?php echo $this->Form->create('Project', array(
        'inputDefaults' => array(
                'class' => 'form-control', 'label' => false, 'div' => false
        )
)) ?>

    <div class="search_box_wrap">
        <div class="clearfix search_box">
            <div class="form-group category">
                <?php echo $this->Form->input('category_id', array(
                        'options' => $categories, 'empty' => $setting['cat1_name'],
                )); ?>
            </div>
            <?php if($setting['cat_type_num'] == 2):?>
                <div class="form-group category">
                    <?php echo $this->Form->input('area_id', array(
                        'options' => $areas, 'empty' => $setting['cat2_name'],
                    )); ?>
                </div>
            <?php endif;?>
            <div class="form-group sort">
                <?php echo $this->Form->input('order', array(
                        'options' => array(
                                '1' => '支援金額が多い順', '2' => '新着順', '3' => '募集終了が近い順'
                        ), 'empty' => 'ソート',
                )); ?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->submit(__('検索'), array('class' => 'btn btn-primary btn-block btn-sm')); ?>
            </div>
        </div>
    </div>

<?php echo $this->Form->end(); ?>

    <h3 class="title"><span class="el-icon-search"></span> 検索結果</h3>
    <div id="grid_container" class="clearfix ">
        <?php foreach($projects as $idx => $project): ?>
            <?php echo $this->element('project_box/project_box_for_normal', array('project' => $project)) ?>
        <?php endforeach; ?>
    </div>

    <div class="container">
        <?php echo $this->element('base/pagination') ?>
    </div>

<?php $this->start('script') ?>
    <script>
        $(document).ready(function () {
            grid_position();
        });
    </script>
<?php $this->end() ?>