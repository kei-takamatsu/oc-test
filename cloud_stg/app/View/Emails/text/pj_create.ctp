<?php echo $user['User']['nick_name'] ? $user['User']['nick_name'] : $user['User']['name'] ?> 様

プロジェクト作成のお申込み、誠にありがとうございます。
確認の上、改めてご連絡いたしますので、
今しばらくお待ちくださいませ。


■お申込みいただいたプロジェクト
ID： <?php echo h($pj['Project']['id'])."\n"?>
名前： <?php echo h($pj['Project']['project_name'])."\n"?>
目標金額： <?php echo h(number_format($pj['Project']['goal_amount']))."\n"?>
概要： <?php echo h($pj['Project']['description'])."\n"?>


