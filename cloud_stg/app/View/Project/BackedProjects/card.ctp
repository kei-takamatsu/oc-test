<?php echo $this->Html->css('pay', null, array('inline' => false)) ?>

<?php echo $this->element('pay/pay_header') ?>

<div class="pay clearfix">

    <div class="pay_content">
        <h3>支援内容を確認して、クレジットカード情報を入力してください！<br>支援確定ボタンを押すと決済が確定します。</h3>

        <div class="col-md-5">
            <h4>支援金額</h4>
            <div class="confirm_box">
			<span style="font-size:20px; font-weight:bold;">
				<?php echo number_format(h($backed_project['amount'])) ?> 円
			</span>
            </div>

            <h4>支援コメント</h4>
            <div class="confirm_box">
                <?php echo !empty($backed_project['comment']) ? nl2br(h($backed_project['comment'])) : '入力なし' ?>
            </div>
            <hr>

            <h4>リターン内容</h4>
            <div class="pay_return">
                <?php echo nl2br($backing_level['BackingLevel']['return_amount']); ?>
            </div>
            <br>
        </div>

        <div class="col-md-7">
            <div class="card_input_box">
                <h3><span class="el-icon-credit-card"></span> クレジットカード情報入力</h3>

                <p>クレジットカードはVISA、Masterがお使いいただけます。</p>

                <?php if(!empty($error)): ?>
                    <div class="error-message" style="padding:10px 0; font-weight:bold;">
                        <?php foreach($error as $e): ?>
                            エラー： <?php echo $e.'<br>' ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php echo $this->Form->create('BackedProject', array(
                        'inputDefaults' => array(
                                'div' => false, 'class' => 'form-control', 'label' => false
                        )
                )) ?>


                <div class="form-group clearfix">
                    <label>カード番号<?php echo $this->Html->image('visa_master.png', array('height' => 20)) ?></label>
                    <?php echo $this->Form->input('card_no', array(
                            'type' => 'text', 'required' => true
                    )) ?>
                </div>

                <div class="form-group clearfix">
                    <label>有効期限</label><br>
                    <?php echo $this->Form->input('month', array(
                            'type' => 'select', 'class' => '', 'options' => $card_months
                    )) ?> 月
                    ／
                    <?php echo $this->Form->input('year', array(
                            'type' => 'select', 'class' => '', 'options' => $card_years
                    )) ?> 年
                </div>

                <div class="form-group clearfix">
                    <label>セキュリティコード</label>
                    <div style="width: 50%;">
                        <?php echo $this->Form->input('code', array(
                                'type' => 'text', 'required' => true
                        )) ?>
                    </div>
                </div>

                <div class="form-group clearfix">
                    <div class="col-sm-offset-2 col-sm-8" style="margin-top:20px;">
                        <?php echo $this->Form->submit('支援確定！', array('class' => 'btn btn-success btn-block card_button')) ?>
                    </div>
                </div>

                <?php echo $this->Form->end() ?>
            </div>
        </div>

    </div>
</div>
