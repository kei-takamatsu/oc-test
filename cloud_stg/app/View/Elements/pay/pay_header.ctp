<div class="pay_header">
    <h1><?php echo h($project['Project']['project_name']); ?></h1>

    <div class="clearfix sub">
        <span class="el-icon-tag"></span>
        <?php echo h($project['Category']['name']); ?>　|　
        <span class="el-icon-child"></span>
        <?php echo h($project['User']['nick_name']) ?>
    </div>
</div>