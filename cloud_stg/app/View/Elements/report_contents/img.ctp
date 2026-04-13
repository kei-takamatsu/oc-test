<div class="content_view" style="margin:0 auto; max-width:500px; text-align:center;">
    <?php if($this->Label->link($content['ReportContent']['img'])){
        echo $this->Label->image($content['ReportContent']['img'], array(
                'class' => 'img-responsive', 'style' => 'margin:0 auto;;'
        ));
    } ?>
    <p class="img_caption">
        <?php echo h($content['ReportContent']['img_caption']) ?>
    </p>
</div>

<div class="content_edit_form clearfix" style="display: none;">
    <?php echo $this->Form->create('ReportContent', array(
            'inputDefaults' => array(
                    'label' => false, 'class' => 'form-control'
            ), 'onsubmit' => 'return false;', 'class' => 'img_form'
    )) ?>

    <p class="hidden-xs">ドラッグアンドドロップでも追加できます</p>

    <div class="form-group">
        <?php echo $this->Form->input('img', array(
                'type' => 'file', 'class' => 'client_resize',
                'onchange' => "client_resize($(this), event, 750, null, 'preview_img".$content['ReportContent']['id']
                              ."', 'img');"
        )); ?>
        <div id="preview_img<?php echo h($content['ReportContent']['id']) ?>"
             style="max-width:400px; margin-top:10px;"></div>
    </div>

    <div class="form-group">
        <?php echo $this->Form->input('img_caption', array(
                'label' => '画像の説明', 'class' => 'form-control img_caption',
                'value' => $content['ReportContent']['img_caption']
        )); ?>
    </div>

    <div class="form-group" id="movie_btns">
        <button type="button"
                onclick="edit_img($(this), event, '<?php echo $this->Html->url(array(
                        'controller' => 'report_contents', 'action' => 'img_edit'
                )) ?>');"
                class="btn btn-info col-xs-3" style="margin-right:20px;">更新
        </button>
        <button type="button" onclick="cancel_edit($(this));" class="btn btn-info col-sm-3" style="margin-right:20px;">
            キャンセル
        </button>
    </div>

    <?php echo $this->Form->end(); ?>
</div>

