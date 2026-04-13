<?php echo $backer['User']['nick_name'] ? $backer['User']['nick_name'] : $backer['User']['name'] ?> 様

『<?php echo $project['Project']['project_name'] ?>』にご支援いただきありがとうございます！
プロジェクトの募集期間終了時に再度募集結果についてご連絡いたします。

■ご支援内容
・日時: <?php echo date('Y年m月d日 H時i分', strtotime($backed_project['BackedProject']['created']))."\n" ?>
・金額： <?php echo number_format($backed_project['BackedProject']['invest_amount']) ?>円
・コメント：
<?php echo $backed_project['BackedProject']['comment']."\n\n" ?>

今後とも、宜しくお願いいたします。

