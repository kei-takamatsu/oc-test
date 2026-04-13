<div class="none new_item clearfix new_text">

    <?php //echo $this->element('project_contents/text_btn_menu')?>

    <div class="form-group">
        <?php echo $this->Form->input('txt_content'.$content_no, array(
                'type' => 'textarea', 'rows' => 5, 'class' => 'form-control text_content', 'label' => false
        )); ?>
    </div>
</div>