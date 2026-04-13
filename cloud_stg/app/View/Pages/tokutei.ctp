<div class="about_box_wrap">
    <div class="about_box">
        <h1>特定商取引法に基づく表記</h1>
        <dl>
            <dt>事業者</dt>
            <dd>
                <?php echo h($setting['company_name']) ?>
            </dd>
        </dl>
        <dl>
            <dt>運営責任者</dt>
            <dd>
                <?php echo h($setting['company_ceo']) ?>
            </dd>
        </dl>
        <dl>
            <dt>所在地</dt>
            <dd>
                <?php echo h($setting['company_address']) ?>
            </dd>
        </dl>
        <dl>
            <dt>電話番号</dt>
            <dd>
                <?php echo !empty($setting['company_tel']) ? h($setting['company_tel']) : '準備中'?>
            </dd>
        </dl>
        <dl>
            <dt>メールアドレス</dt>
            <dd>
                <?php echo str_replace("@", "[at]", h($setting['from_mail_address'])) ?>
            </dd>
        </dl>
        <dl>
            <dt>ホームページ</dt>
            <dd>
                <?php echo $this->Html->link(h($setting['company_url']), h($setting['company_url']), array('target' => '_blank')) ?>
            </dd>
        </dl>
        <dl>
            <dt>販売価格</dt>
            <dd>
                各プロジェクトに表記された価格に準じます。各プロジェクトの募集期間の終了日に決済が完了します。また、各プロジェクトで定まった募集期間内に定まった目標金額が成立しない場合は、募集期間の終了日になった場合でも決済はされません。
            </dd>
        </dl>
        <dl>
            <dt>引渡時期</dt>
            <dd>各プロジェクトの募集期間終了日に代金支払が確認できた時点でお客様への引渡しが完了するものとします。</dd>
        </dl>
        <dl>
            <dt>購入可能な範囲の制限</dt>
            <dd>購入は日本国内に限ります。</dd>
        </dl>
        <dl>
            <dt>販売価格以外の必要手数料</dt>
            <dd>なし。（支払手数料やリターンに関する送料などは例外を除いて請求致しません。）</dd>
        </dl>
        <dl>
            <dt>注文方法</dt>
            <dd>インターネット</dd>
        </dl>
        <dl>
            <dt>支払方法</dt>
            <dd>クレジットカードによる支払い。</dd>
        </dl>
        <dl>
            <dt>支払時期</dt>
            <dd>クレジットカードによるお支払いは各プロジェクトで定まった募集期間内に定まった目標額が成立した場合に支払が完了します。</dd>
        </dl>
        <dl>
            <dt>返金・キャンセルについて</dt>
            <dd>
                一定期間内に販売された金額が、各プロジェクトに設定された最低目標額を超えた場合のみ販売が決定するという販売ルール上、
                キャンセルは承っておりません。キャンセルが出ることで最低目標数を下回る場合、起案者や他のお客様にご迷惑がかかりますので予めご了承ください。
            </dd>
        </dl>

    </div>
</div>
