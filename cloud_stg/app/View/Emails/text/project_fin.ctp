<?php echo $user['User']['nick_name'] ? $user['User']['nick_name'] : $user['User']['name'] ?> 様

下記プロジェクトの募集期間が終了しました。

<?php if($ok_ng): ?>
おめでとうございます！プロジェクトが成功しました！
支援総額から管理手数料を除いた金額をお振込しますのでいましばらくお待ちください。
<?php else: ?>
残念ながらプロジェクトは目標金額に到達しませんでした。
<?php endif; ?>

・プロジェクト名： 『<?php echo $project['Project']['project_name'] ?>』
・目標金額： <?php echo number_format($project['Project']['goal_amount']) ?>円
・支援総額： <?php echo number_format($project['Project']['collected_amount']) ?>円
・支援人数： <?php echo number_format($project['Project']['backers']) ?>人


この度は、プロジェクトを投稿いただきましてありがとうございました。
今後とも、宜しくお願いいたします。

