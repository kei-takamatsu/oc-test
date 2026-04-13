<?php echo $this->Html->css('pay', null, array('inline' => false)) ?>

<?php echo $this->element('pay/pay_header') ?>

<div class="pay add">

    <div class="pay_content clearfix">
        <h3>支援金額と支援コメントを入力してください！</h3>

        <?php echo $this->Form->create('BackedProject', array(
                'inputDefaults' => array(
                        'div' => false, 'class' => 'form-control'
                )
        )) ?>

        <div class="form-group">
            <label>支援金額</label>
            <div class="input-group">
                <?php echo $this->Form->input('invest_amount', array(
                        'value' => $backing_level['BackingLevel']['invest_amount'], 'label' => false,
                )) ?>
                <span class="input-group-addon">円</span>
            </div>

            <span class="<?php echo !empty($error) ? 'error-message' : '' ?>">
			※ <?php echo number_format($backing_level['BackingLevel']['invest_amount']) ?>
                円以上を入力してください
			</span>
        </div>

        <div class="form-group">
            <?php echo $this->Form->input('comment', array(
                    'type' => 'textarea', 'rows' => 4, 'label' => '支援コメント'
            )) ?>
        </div>

        <?php if($backing_level['BackingLevel']['delivery'] == 2): ?>
            <div class="form-group">
                <?php echo $this->Form->input('User.name', array('label' => '氏名'))?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('User.receive_address', array(
                        'type' => 'textarea', 'rows' => 4, 'label' => 'リターン配送先住所',
                )) ?>
            </div>
        <?php endif; ?>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-8" style="margin-top:20px;">
                <?php echo $this->Form->submit('次へ', array('class' => 'btn btn-success btn-block')) ?>
            </div>
        </div>

        <?php echo $this->Form->end() ?>
    </div>
</div>
