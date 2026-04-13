<div class="setting_title">
    <h2>ユーザー一覧</h2>
</div>
<br><br>

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
    <hr>
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
                        <a href="<?php echo $this->Html->url('/admin/admin_users/view/'.$u['User']['id']) ?>"
                           class="btn btn-success">詳細</a>
                        <a href="<?php echo $this->Html->url('/admin/admin_users/edit/'.$u['User']['id']) ?>"
                           class="btn btn-primary">編集</a>
                        <a href="<?php echo $this->Html->url('/users/view/'.$u['User']['id']) ?>" class="btn btn-info"
                           target="_blank">プロフィール画面</a>
                        <?php
                        echo$this->Form->postLink(
                            '<button class="btn btn-danger">削除</button>',
                            '/admin/admin_users/delete/'.$u['User']['id'],
                            array('class'=>'',
                                  'escape'=>false),
                            '支援されている方、プロジェクト公開中の方を削除するのは危険ですので、十分注意してください。削除しますか？'
                        );
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php echo $this->element('base/pagination') ?>
    <?php endif; ?>
</div>


