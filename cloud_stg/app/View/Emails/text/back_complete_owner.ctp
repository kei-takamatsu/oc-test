<?php echo $owner['User']['nick_name'] ? $owner['User']['nick_name'] : $owner['User']['name'] ?> 様

『<?php echo $project['Project']['project_name'] ?>』に支援がありました！

■プロジェクト概要
・プロジェクト名： 『<?php echo $project['Project']['project_name'] ?>』
・目標金額： <?php echo number_format($project['Project']['goal_amount']) ?>円
・支援総額： <?php echo number_format($project['Project']['collected_amount']) ?>円
・支援人数： <?php echo number_format($project['Project']['backers']) ?>人


■支援内容
・支援者： <?php echo ($backer['User']['nick_name'] ? $backer['User']['nick_name'] : $backer['User']['name'])."\n" ?>
・支援日時: <?php echo date('Y年m月d日 H時i分', strtotime($backed_project['BackedProject']['created']))."\n" ?>
・支援金額： <?php echo number_format($backed_project['BackedProject']['invest_amount']) ?>円
・支援コメント：
<?php echo $backed_project['BackedProject']['comment']."\n\n" ?>

今後とも、宜しくお願いいたします。