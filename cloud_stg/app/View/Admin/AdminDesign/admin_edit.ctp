<?php echo $this->Html->script('client_resize', array('inline' => false)) ?>

    <div class="bread">
        <?php echo $this->Html->link('デザイン', '/admin/admin_design/edit') ?> &gt;
        ロゴ・色
    </div>
    <div class="setting_title">
        <h2>ロゴ・色の設定</h2>
    </div>
<?php echo $this->element('admin/design_menu', array('mode' => 'logo')) ?>


    <div class="setting_box">
        <div class="container">
            <?php echo $this->Form->create('Setting', array(
                    'type' => 'file', 'inputDefaults' => array('class' => 'form-control')
            )) ?>

            <div class="clearfix">
                <div class="col-md-6">
                    <h4>ロゴ</h4>
                    <div class="form-group">
                        <?php echo $this->Form->input('logo', array(
                                'type' => 'file', 'label' => 'ロゴ画像', 'class' => 'client_resize',
                                'onchange' => "client_resize($(this), event, null, 30, 'preview_img', 'logo');"
                        )); ?>
                        <br>
                        <div id="preview_img">
                            <?php
                            if(!empty($setting['logo'])){
                                echo $this->Label->image($setting['logo'], array(
                                        'class' => 'img-responsive', 'style' => 'margin-top:10px;'
                                ));
                            }
                            ?>
                        </div>
                    </div>

                    <h4>基本</h4>
                    <div class="clearfix">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?php echo $this->Form->input('link_color', array(
                                        'label' => 'リンク文字', 'class' => 'form-control color'
                                )) ?>
                            </div>
                            <div class="form-group">
                                <?php echo $this->Form->input('back1', array(
                                        'label' => '背景1', 'class' => 'form-control color'
                                )) ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?php echo $this->Form->input('back2', array(
                                        'label' => '背景2', 'class' => 'form-control color'
                                )) ?>
                            </div>
                            <div class="form-group">
                                <?php echo $this->Form->input('border_color', array(
                                        'label' => '枠線', 'class' => 'form-control color'
                                )) ?>
                            </div>
                        </div>
                    </div>

                    <h4>フッター</h4>
                    <div class="clearfix">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?php echo $this->Form->input('footer_back', array(
                                        'label' => '背景', 'class' => 'form-control color'
                                )) ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?php echo $this->Form->input('footer_color', array(
                                        'label' => '文字', 'class' => 'form-control color'
                                )) ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <h4>メニューバー</h4>
                    <div class="clearfix">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?php echo $this->Form->input('top_back', array(
                                        'label' => '背景', 'class' => 'form-control color'
                                )) ?>
                            </div>
                            <div class="form-group">
                                <?php echo $this->Form->input('top_color', array(
                                        'label' => '文字', 'class' => 'form-control color'
                                )) ?>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <label>不透明度</label>
                            <div class="input-group">
                                <?php echo $this->Form->input('top_alpha', array(
                                        'label' => false, 'class' => 'form-control'
                                )) ?>
                                <span class="input-group-addon">%</span>
                            </div>
                        </div>
                    </div>


                    <h4>達成率グラフ</h4>
                    <div class="clearfix">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?php echo $this->Form->input('graph_back', array(
                                        'label' => '背景', 'class' => 'form-control color'
                                )) ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="width:100%;">
                <div class="col-sm-offset-1 col-sm-10" style="margin-top:20px;">
                    <div class="form-group">
                        <input type="submit"
                               onclick="save_form_data($(this), event, '<?php echo $this->Html->url(array('action' => 'admin_edit')) ?>'); return false;"
                               class="btn btn-primary btn-block" value="登録">
                    </div>
                </div>
            </div>
            <?php echo $this->Form->end() ?>
        </div>
    </div>

<?php echo $this->Html->script('jscolor/jscolor', array('inline' => false)) ?>