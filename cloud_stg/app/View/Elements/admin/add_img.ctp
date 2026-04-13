<div class="none new_item new_img">
    <div class="form-group">
        <?php echo $this->Form->input('top_box_img'.$content_no, array(
                'type' => 'file', 'label' => false, 'class' => 'client_resize',
                'onchange' => "client_resize($(this), event, 600, null, 'preview_top_box_img".$content_no
                              ."', 'top_box_img".$content_no."');"
        )); ?>

        <br>
        <div id="preview_top_box_img<?php echo $content_no ?>">
            <?php if($this->Label->url($this->request->data['Setting']['top_box_img'.$content_no])){
                echo $this->Label->image($this->request->data['Setting']['top_box_img'
                                                                         .$content_no], array('class' => 'img-responsive'));
            } ?>
        </div>
    </div>
</div>
