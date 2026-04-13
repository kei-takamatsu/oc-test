<div class="bread">
    <?php echo $this->Html->link('プロジェクト', '/admin/admin_projects/') ?> &gt;
    <?php echo $this->Html->link('プロジェクト一覧', '/admin/admin_projects/') ?> &gt;
    <?php echo $this->Html->link('プロジェクト編集', '/admin/admin_projects/edit/'.$project['Project']['id']) ?> &gt;
    支援パターン
</div>
<div class="setting_title">
    <h2>プロジェクトの支援パターンの編集</h2>
</div>
<?php echo $this->element('admin/setting_project_menu', array('mode' => 'back')) ?>

<?php echo $this->Form->create('Project', array(
        'inputDefaults' => array(
                'class' => 'form-control', 'div' => false
        )
)); ?>
<div class="max_backing_level_num">
    <div class="container">
        <div class="form-group">
            <?php echo $this->Form->input('Project.max_back_level', array(
                    'required' => 'required', 'id' => 'back_level', 'label' => '支援パターン数', 'options' => array(
                            '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7',
                            '8' => '8', '9' => '9', '10' => '10'
                    ), 'empty' => '------'
            )); ?>
        </div>
    </div>
</div>

<div class="container">
    <div id="blevel" class="clearfix">
        <?php if(isset($this->request->data['BackingLevel'])): ?>
            <?php $i = 0; ?>

            <?php foreach($this->request->data['BackingLevel'] as $level): ?>
                <div class="levels clearfix" index="<?php echo $i + 1; ?>">
                    <h4>支援パターン<?php echo $i + 1; ?></h4>
                    <br>
                    <div class="col-md-4">
                        <div class="form-group">
                            <?php echo $this->Form->input('BackingLevel.'.($i).'.id', array('type' => 'hidden')); ?>
                            <?php echo $this->Form->input('BackingLevel.'.($i).'.name', array('type' => 'hidden')); ?>

                            <label>最低支援額</label>
                            <div class="input-group">
                                <?php echo $this->Form->input('BackingLevel.'.($i).'.invest_amount', array(
                                        'required' => 'required', 'label' => false
                                ));
                                ?>
                                <span class="input-group-addon">円</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>最大支援回数</label>
                            <div class="input-group">
                                <?php echo $this->Form->input('BackingLevel.'.($i)
                                                              .'.max_count', array('label' => false)) ?>
                                <span class="input-group-addon">回</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>リターン受け渡し手段</label>
                            <?php echo $this->Form->input('BackingLevel.'.($i).'.delivery', array(
                                    'label' => false, 'type' => 'select', 'options' => Configure::read('DELIVERY')
                            )) ?>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <?php echo $this->Form->input('BackingLevel.'.($i).'.return_amount', array(
                                'type' => 'textarea', 'required' => 'required', 'label' => 'リターン内容', 'rows' => 9
                        ));
                        ?>
                    </div>
                </div>
                <?php $i++; ?>
            <?php endforeach; ?>

        <?php endif; ?>
    </div>

    <div class="form-group">
        <div class="col-xs-offset-1 col-xs-10" style="margin-top:20px;">
            <?php echo $this->Form->submit('更新', array(
                    'class' => 'btn-block btn btn-primary', 'style' => 'margin-left:10px;'
            )) ?>
        </div>
    </div>
    <br>
    <?php echo $this->Form->end(); ?>
</div>


<?php $this->start('script') ?>
<script>
    var clone = '' +
            '<div class="levels clearfix" index="@">' +
            '<h4>支援パターン@</h4>' +
            '<br>' +
            '<div class="col-md-4">' +
            '<div class="form-group">' +
            '<?php echo $this->Form->input('BackingLevel.@.name', array(
                    'type' => 'hidden', 'value' => 'level '.'@'
            ));?>' +
            '<label>最低支援額</label>' +
            '<div class="input-group">' +
            '<?php echo $this->Form->input('BackingLevel.@.invest_amount', array(
                    'required' => 'required', 'label' => false
            ));
                    ?>' +
            '<span class="input-group-addon">円</span>' +
            '</div>' +
            '</div>' +

            '<div class="form-group">' +
            '<label>最大支援回数</label>' +
            '<div class="input-group">' +
            '<?php echo $this->Form->input('BackingLevel.@.max_count', array('label' => false)) ?>' +
            '<span class="input-group-addon">回</span>' +
            '</div>' +
            '</div>' +

            '<div class="form-group">' +
            '<label>リターン受け渡し手段</label>' +
            '<?php echo str_replace(array(
                    "\r\n", "\r", "\n"
            ), '', $this->Form->input('BackingLevel.@.delivery', array(
                    'label' => false, 'div' => false, 'type' => 'select', 'options' => Configure::read('DELIVERY')
            ))); ?>' +
            '</div>' +
            '</div>' +


            '<div class="col-md-8">' +
            '<?php echo $this->Form->input('BackingLevel.@.return_amount', array(
                    'type' => 'textarea', 'required' => 'required', 'label' => 'リターン内容', 'rows' => 9
            ));
                    ?>' +

            '</div></div>';

    $(document).ready(function () {
        $('#back_level').change(function () {
            var cont = $('#blevel').html();
            cont = cont.trim();

            if (cont == '') {
                var max_level = $('#back_level').val();
                $(".levels").remove();

                for (var i = 0; i < max_level; i++) {
                    var row = clone.replace(/@/g, i + 1);
                    $('#blevel').append(row);
                }
            } else {
                var existing = parseInt($('div.levels').last().attr('index'));
                var current = parseInt($('#back_level').val());

                if (current < existing) {
                    // Remove the difference
                    for (j = 1; j <= existing; j++) {
                        if (j > current) {
                            // Remove
                            $('div[index=' + j + ']').remove();
                        }
                    }
                } else {
                    // Add the difference
                    for (k = 1; k <= current; k++) {
                        if (k > existing) {
                            var row = clone.replace(/@@/g, k - 1);
                            row = row.replace(/[@]/g, k);
                            $('#blevel').append(row);
                        }
                    }
                }
            }

        });
    });
</script>
<?php $this->end() ?>