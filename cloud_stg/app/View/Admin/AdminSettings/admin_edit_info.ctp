<div class="setting_title">
    <h2>基本情報の設定</h2>
</div>

<div class="container">
    <?php echo $this->Form->create('Setting', array(
            'type' => 'file', 'inputDefaults' => array('class' => 'form-control')
    )) ?>

    <div class="col-md-6">
<!--        <h4>SSL利用有無</h4>-->
<!--        <div class="form-group">-->
<!--            <label>SSL利用有無(https接続の強制有無）</label>-->
<!--            <p>SSLを利用し、本サイトをHttps接続にする場合、「はい」を選択してください。</p>-->
<!--            --><?php //echo $this->Form->input('https_flag', array(
//                    'type' => 'select', 'label' => false,
//                    'selected' => (int)$this->request->data['Setting']['https_flag'], 'options' => array(
//                            1 => 'はい', 0 => 'いいえ'
//                    )
//            )) ?>
<!--        </div>-->
        <h4>サイト情報</h4>
        <div class="form-group">
            <?php echo $this->Form->input('site_name', array('label' => 'サイト名')) ?>
        </div>

        <div class="form-group">
            <label>手数料率</label><br>
            <p>
                プロジェクトが成功した場合の支援総額に対する手数料率。(20%の場合20と入力してください)
            </p>
            <div class="input-group">
                <?php echo $this->Form->input('fee', array('label' => false)) ?>
                <span class="input-group-addon">%</span>
            </div>
        </div>

        <h4>メール</h4>
        <div class="form-group">
            <?php echo $this->Form->input('from_mail_address', array('label' => '送信元メールアドレス')) ?>
        </div>

        <div class="form-group">
            <?php echo $this->Form->input('admin_mail_address', array('label' => '管理者通知用メールアドレス')) ?>
        </div>

        <div class="form-group">
            <?php echo $this->Form->input('mail_signature', array('label' => 'メールの署名')) ?>
        </div>

        <h4>運営者情報</h4>
        <div class="form-group">
            <?php echo $this->Form->input('company_name', array('label' => '事業者')) ?>
        </div>

        <div class="form-group">
            <?php echo $this->Form->input('company_type', array(
                    'label' => '法人 or 個人', 'type' => 'select', 'options' => Configure::read('COMPANY_TYPE')
            )) ?>
        </div>

        <div class="form-group">
            <?php echo $this->Form->input('company_url', array('label' => '運営者URL')) ?>
        </div>

        <div class="form-group">
            <?php echo $this->Form->input('company_ceo', array('label' => '運営責任者氏名')) ?>
        </div>

        <div class="form-group">
            <?php echo $this->Form->input('company_address', array('label' => '所在地')) ?>
        </div>
        <div class="form-group">
            <?php echo $this->Form->input('company_tel', array('label' => '電話番号')) ?>
        </div>
        <div class="form-group">
            <?php echo $this->Form->input('copy_right', array('label' => 'コピーライトに表示する名前')) ?>
        </div>
    </div>

    <div class="col-md-6">
        <h4>HTMLヘッダ</h4>
        <div class="form-group">
            <?php echo $this->Form->input('site_title', array('label' => 'タイトル')) ?>
        </div>

        <div class="form-group">
            <?php echo $this->Form->input('site_description', array('label' => 'サイトの説明')) ?>
        </div>

        <div class="form-group">
            <?php echo $this->Form->input('site_keywords', array('label' => 'サイトのキーワード')) ?>
        </div>

        <h4>GMOペイメント</h4>
        <div class="form-group">
            <?php echo $this->Form->input('gmo_id', array(
                    'type' => 'text', 'label' => 'GMOペイメント Shop ID'
            )) ?>
        </div>
        <div class="form-group">
            <?php echo $this->Form->input('gmo_password', array('label' => 'GMOペイメント Shop パスワード')) ?>
        </div>

        <h4>Twitter</h4>
        <p>Twitterログインに必要となります。</p>
        <div class="form-group">
            <?php echo $this->Form->input('twitter_api_key', array('label' => 'Twitter API Key')) ?>
        </div>
        <div class="form-group">
            <?php echo $this->Form->input('twitter_api_secret', array('label' => 'Twitter API Secret')) ?>
        </div>

        <h4>Facebook</h4>
        <p>Facebookログインに必要となります。</p>
        <div class="form-group">
            <?php echo $this->Form->input('facebook_api_key', array('label' => 'Facebook API Key')) ?>
        </div>
        <div class="form-group">
            <?php echo $this->Form->input('facebook_api_secret', array('label' => 'Faceook API Key')) ?>
        </div>
        <div class="form-group">
            <label style="margin-bottom:15px;">Facebookでシェアされたときに表示する画像(推奨サイズ：750px x 393px)</label><br>
            <?php echo $this->Form->input('facebook_img', array(
                    'label' => false, 'type' => 'file', 'class' => ''
            )) ?>
            <br>
            <?php
            if(!empty($setting['facebook_img'])){
                echo $this->Label->image($setting['facebook_img'], array('class' => 'img img-responsive'));
            }
            ?>
        </div>

        <h4>Google Analytics コード</h4>
        <div class="form-group">
            <?php echo $this->Form->input('google_analytics', array('label' => 'Google Analytics code', 'type' => 'textarea')) ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-xs-offset-1 col-xs-10">
            <br><?php echo $this->Form->submit('登録', array('class' => 'btn btn-primary btn-block')) ?>
        </div>
    </div>
    <?php echo $this->Form->end() ?>
</div>

