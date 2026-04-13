<div class="bread">
    <?php echo $this->Html->link('プロジェクト', '/admin/admin_projects/') ?>
    &gt; コメント一覧
</div>
<div class="setting_title">
    <h2>コメント一覧</h2>
</div>
<?php echo $this->element('admin/setting_project_main_menu', array('mode' => 'comments')) ?>
<br><br>
<div class="container">
    <?php echo $this->Form->create('Comment', array('inputDefaults' => array('class' => 'form-control'))) ?>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <?php echo $this->Form->input('pj_id', array(
                        'label' => 'プロジェクトID', 'type' => 'text'
                )) ?>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <?php echo $this->Form->input('word', array('label' => 'コメント内容')) ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <button type="submit" class="btn btn-primary">検索</button>
            </div>
        </div>
    </div>
    <?php echo $this->Form->end() ?>
    <hr>

    <?php if(!empty($comments)): ?>
        <table class="table table-bordered">
            <tr>
                <th>ID</th>
                <th>プロジェクトID</th>
                <th>ユーザーID</th>
                <th>コメント</th>
                <th></th>
            </tr>
            <?php foreach($comments as $c): ?>
                <tr>
                    <td><?php echo h($c['Comment']['id']) ?></td>
                    <td><?php echo h($c['Comment']['project_id']) ?></td>
                    <td><?php echo h($c['Comment']['user_id']) ?></td>
                    <td><?php echo h($c['Comment']['comment']) ?></td>
                    <td>
                        <?php
                        echo $this->Form->postLink('<button class="btn btn-danger">削除</button>', '/admin/admin_comments/delete/'
                                                                                                 .$c['Comment']['id'], array(
                                'class' => '', 'escape' => false
                        ), '削除しますか？');
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php echo $this->element('base/pagination') ?>
    <?php endif; ?>
</div>