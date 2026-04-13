<?php echo $to_user['nick_name'] ? h($to_user['nick_name']) : h($to_user['name']) ?> 様

いつも<?php echo !empty($setting['site_name']) ? h($setting['site_name']) : SITE_NAME ?>
をご利用いただき、ありがとうございます。

<?php echo $from_user['nick_name'] ? h($from_user['nick_name']) : h($from_user['name']) ?>さんからメッセージが届きました。
ご確認を宜しくお願いいたします。

<?php echo $url."\n" ?>

メッセージ：
<?php echo $this->Text->truncate($msg, 100, array(
                'exact' => true, 'html' => false
        ))."\n" ?>

