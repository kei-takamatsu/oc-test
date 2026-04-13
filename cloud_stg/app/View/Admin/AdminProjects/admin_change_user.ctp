<div class="bread">
    <?php echo $this->Html->link('プロジェクト', '/admin/admin_projects/') ?> &gt;
    <?php echo $this->Html->link('プロジェクト一覧', '/admin/admin_projects/') ?> &gt;
    <?php echo $this->Html->link('プロジェクト編集', '/admin/admin_projects/edit/'.$project['Project']['id']) ?> &gt;
    <?php echo $this->Html->link('基本情報', '/admin/admin_projects/edit/'.$project['Project']['id']) ?> &gt;
    プロジェクトの作成ユーザ変更
</div>
<div class="setting_title">
    <h2>プロジェクトの作成ユーザ変更</h2>
</div>
<?php echo $this->element('admin/setting_project_menu', array('mode' => 'base')) ?>
<br>
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <?php echo $this->Form->create('User') ?>
            <div class="form-group">
                <?php echo $this->Form->input('email_like', array(
                        'class' => 'form-control', 'label' => 'メールアドレスで検索', 'required' => false
                )) ?>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">検索</button>
            </div>
            <?php echo $this->Form->end() ?>
        </div>
    </div>
    <?php if(!empty($users)): ?>
        <br>

        <table class="table table-bordered">
            <tr>
                <th>ID</th>
                <th>ユーザーネーム</th>
                <th>メールアドレス</th>
                <th></th>
            </tr>
            <?php foreach($users as $u): ?>
                <tr>
                    <td><?php echo h($u['User']['id']) ?></td>
                    <td><?php echo h($u['User']['nick_name']) ?></td>
                    <td><?php echo h($u['User']['email']) ?></td>
                    <td>
                        <?php
                        echo $this->Form->postLink('<button class="btn btn-success">変更</button>', '/admin/admin_projects/change_user/'
                                                                                                  .$project['Project']['id']
                                                                                                  .'/'
                                                                                                  .$u['User']['id'], array(
                                'class' => '', 'escape' => false
                        ), '作成ユーザーを変更しますか？');
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php echo $this->element('base/pagination') ?>
    <?php endif; ?>
</div>

