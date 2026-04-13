<?php echo $this->Html->script('client_resize', array('inline' => false)) ?>

<div class="bread">
    <?php echo $this->Html->link('デザイン', '/admin/admin_design/edit') ?> &gt;
    トップページ
</div>
<div class="setting_title">
    <h2>トップページのデザインの設定</h2>
</div>
<?php echo $this->element('admin/design_menu', array('mode' => 'top')) ?>


<div class="setting_box">
    <div class="container">
        <?php echo $this->Form->create('Setting', array(
                'type' => 'file', 'inputDefaults' => array('class' => 'form-control')
        )) ?>

        <div class="clearfix">
            <div class="col-md-6">
                <h4>トップページBOX 画像</h4>
                <div class="clearfix">
                    <div class="form-group">
                        <?php echo $this->Form->input('top_box_sm', array(
                                'type' => 'file', 'label' => 'スマホ用 (幅〜750px)', 'class' => 'client_resize',
                                'onchange' => "client_resize($(this), event, 750, null, 'preview_top_box_sm', 'top_box_sm');"
                        )); ?>

                        <br>
                        <div id="preview_top_box_sm">
                            <?php if($this->Label->url($this->request->data['Setting']['top_box_sm'])){
                                echo $this->Label->image($this->request->data['Setting']['top_box_sm'], array(
                                        'class' => 'img-responsive', 'style' => 'margin:10px 0 0 0;'
                                ));
                            } ?>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <?php echo $this->Form->input('top_box_pc', array(
                                'type' => 'file', 'label' => 'タブレット・PC用 (幅〜1500px)', 'class' => 'client_resize',
                                'onchange' => "client_resize($(this), event, 1500, null, 'preview_top_box_pc', 'top_box_pc');"
                        )); ?>

                        <br>
                        <div id="preview_top_box_pc">
                            <?php if($this->Label->url($this->request->data['Setting']['top_box_pc'])){
                                echo $this->Label->image($this->request->data['Setting']['top_box_pc'], array(
                                        'class' => 'img-responsive', 'style' => 'margin:10px 0 0 0;'
                                ));
                            } ?>
                        </div>
                    </div>
                </div>

                <h4>トップページBOX 設定</h4>
                <div class="clearfix">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?php echo $this->Form->input('top_box_height', array('label' => '高さ')) ?>
                        </div>
                        <div class="form-group">
                            <?php echo $this->Form->input('top_box_color', array(
                                    'label' => '文字色', 'class' => 'color form-control'
                            )) ?>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <?php echo $this->Form->input('top_box_back', array(
                                    'label' => '背景色', 'class' => 'color form-control'
                            )) ?>
                        </div>
                        <div class="form-group">
                            <label>背景の暗さ</label>
                            <div class="input-group">
                                <?php echo $this->Form->input('top_box_black', array(
                                        'label' => false, 'class' => 'form-control'
                                )) ?>
                                <span class="input-group-addon">%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <h4>コンテンツ設定</h4>
                <div class="clearfix">
                    <div class="form-group">
                        <?php echo $this->Form->input('top_box_content_num', array(
                                'label' => 'コンテンツ数', 'type' => 'select', 'options' => array(
                                        '1' => 1, '2' => 2
                                ), 'class' => 'content_num form-control', 'onchange' => 'change_content_num($(this));'
                        )) ?>
                    </div>
                </div>

                <h5>コンテンツ1</h5>
                <div class="form-group">
                    <?php echo $this->Form->input('content_position1', array(
                            'label' => '配置', 'type' => 'select', 'options' => array(
                                    '上' => '上', '中' => '中央', '下' => '下'
                            )
                    )); ?>
                </div>
                <div class="form-group">
                    <?php echo $this->Form->input('content_type1', array(
                            'label' => '種類', 'type' => 'select', 'class' => 'content1 form-control', 'options' => array(
                                    'text' => 'テキスト', 'movie' => '動画', 'img' => '画像'
                            ), 'onchange' => 'item_change($(this))'
                    )) ?>
                </div>

                <?php echo $this->element('admin/add_text', array('content_no' => 1)) ?>
                <?php echo $this->element('admin/add_img', array('content_no' => 1)) ?>
                <?php echo $this->element('admin/add_movie', array('content_no' => 1)) ?>
                <div id="content2" class="none">
                    <h5>コンテンツ2</h5>
                    <div class="form-group">
                        <?php echo $this->Form->input('content_position2', array(
                                'label' => '配置', 'type' => 'select', 'options' => array(
                                        '上' => '上', '中' => '中央', '下' => '下'
                                )
                        )); ?>
                    </div>
                    <div class="form-group">
                        <?php echo $this->Form->input('content_type2', array(
                                'label' => '種類', 'type' => 'select', 'class' => 'content2 form-control',
                                'options' => array(
                                        'text' => 'テキスト', 'movie' => '動画', 'img' => '画像'
                                ), 'onchange' => 'item_change($(this))'
                        )) ?>
                    </div>

                    <?php echo $this->element('admin/add_text', array('content_no' => 2)) ?>
                    <?php echo $this->element('admin/add_img', array('content_no' => 2)) ?>
                    <?php echo $this->element('admin/add_movie', array('content_no' => 2)) ?>
                </div>
            </div>
        </div>

        <div style="width:100%;">
            <div class="col-sm-offset-1 col-sm-10" style="margin-top:20px;">
                <div class="form-group">
                    <input type="submit"
                           onclick="save_form_data($(this), event, '<?php echo $this->Html->url(array('action' => 'edit_top')) ?>'); return false;"
                           class="btn btn-primary btn-block" value="登録">
                </div>
            </div>
        </div>

        <?php echo $this->Form->end() ?>
    </div>
</div>

<?php $this->start('script') ?>
<script>
    $(document).ready(function () {
        if ($('.content_num').val() == 2) $('#content2').show();
        $('.content1').closest('.form-group').nextAll('.new_' + $('.content1').val()).show();
        $('.content2:visible').closest('.form-group').nextAll('.new_' + $('.content2').val()).show();
    });

    function change_content_num(t) {
        var content_num = t.val();
        if (content_num == 1) {
            $('#content2').hide();
        } else {
            $('#content2').show();
        }
    }

    function item_change(t) {
        var type = t.val();
        t.closest('.form-group').nextAll('.new_item').hide();

        if (type == 'text') {
            t.closest('.form-group').nextAll('.new_text').show();

        } else if (type == 'img') {
            t.closest('.form-group').nextAll('.new_img').show();

        } else { //movie
            t.closest('.form-group').nextAll('.new_movie').show();
            resize_movie();
        }
    }

    function check_movie_type(t) {
        var type = check_movie(t);
        if (!type) {
            t.val('');
            return;
        } else {
            t.val(type[1]);
            t.closest('.form-group').next('.movie_type').val(type[0]);
        }
    }

    //タグで囲む
    function wrap(t, tag_name) {
        wrap_tag(t.parent().next().children('div').children('.text_content'), tag_name);
    }
</script>
<?php $this->end() ?>

<?php echo $this->Html->script('jscolor/jscolor', array('inline' => false)) ?>

