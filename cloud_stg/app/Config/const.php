<?php
//ユーザ権限
define('ADMIN_ROLE', 1); //管理者
define('USER_ROLE', 2); //通常ユーザ
//メール設定
define('CONTACT_SUBJECT', 'お問い合わせありがとうございます');
define('CONFIRM_MAIL_ADDRESS_SUBJECT', 'メールアドレス登録のご確認');
define('FORGOT_PASS_SUBJECT', 'パスワードの再設定');
define('RESET_PASS_COMPLETE_SUBJECT', 'パスワードの再設定が完了しました');
define('RESET_LOCK_SUBJECT', 'アカウントがロックされています');
define('REGISTERED_SUBJECT', 'ユーザ登録が完了しました！');
define('BACK_COMPLETE_BACKER_SUBJECT', 'ありがとうございます！支援が完了しました！');
define('BACK_COMPLETE_OWNER_SUBJECT', '支援がありました！');
define('CANCEL_COMPLETE_SUBJECT', '支援したプロジェクトが終了しました');
define('EXEC_COMPLETE_SUBJECT', '支援したプロジェクトが成功しました！');
define('PROJECT_FIN_SUBJECT', 'プロジェクトが終了しました');
define('MESSAGED_SUBJECT', 'メッセージが届きました');
define('PJ_CREATE_SUBJECT', 'プロジェクト作成のお申し込みありがとうございます');
//twitter画像登録時に使う（ユーザプロフィール画像のフィールド名）
define('USER_IMG_FIELD_NAME', 'img');
//決済（取引登録完了）ステータス
define('STATUS_KARIURIAGE', '仮売上完了');
//支援ステータス
Configure::write('STATUSES_FOR_OPEN', array(
        0 => '仮売上完了', 1 => '売上確定', 2 => 'プロジェクト失敗',
));
//支援の売上確定ステータス
Configure::write('STATUSES_OK', array(0 => '売上確定',));
//支援の失敗ステータス
Configure::write('STATUSES_NG', array(0 => 'プロジェクト失敗',));
//募集期間
//変更するときは、モデルのバリデーションも変更が必要
Configure::write('COLLECTION_TERM', array(
        1 => 30, 2 => 40, 3 => 50, 4 => 60, 5 => 70, 6 => 80
));
//リターン受け渡し手法
Configure::write('DELIVERY', array(
        1 => 'メール', 2 => '郵送', 3 => '対面'
));
//事業者の法人or個人
Configure::write('COMPANY_TYPE', array(
        1 => '法人', 2 => '個人'
));