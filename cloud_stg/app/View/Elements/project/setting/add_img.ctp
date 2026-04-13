<div class="new_item new_img">
    <div class="form-group">
        <label>サムネイル画像（750px x 450px）</label>
        <br><br>
        <?php echo $this->Form->input('pic', array(
                'type' => 'file', 'class' => 'client_resize',
                'onchange' => "client_resize($(this), event, 750, 450, 'preview_pic', 'pic');", 'label' => false
        )); ?>
    </div>
    <br>
    <div id="preview_pic">
        <?php if($this->Label->url($project['Project']['pic'])){
            echo $this->Label->image($project['Project']['pic'], array('class' => 'img-responsive'));
        } ?>
    </div>
</div>