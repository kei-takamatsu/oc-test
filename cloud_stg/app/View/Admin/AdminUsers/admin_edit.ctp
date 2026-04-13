<div class="bread">
    <?php echo $this->Html->link('ユーザ一覧', '/admin/admin_users/') ?> &gt;
    ユーザー編集
</div>
<div class="setting_title">
    <h2>ユーザー編集</h2>
</div>
<br><br>

<div class="container">
    <?php echo $this->Form->create('User', array('inputDefaults' => array('class' => 'form-control', 'label' => false)))?>
    <table class="table table-bordered">
        <tr>
            <th style="width:140px;">id</th>
            <td><?php echo h($u['id']) ?></td>
        </tr>
        <tr>
            <th>ユーザーネーム</th>
            <td>
                <?php echo $this->Form->input('nick_name')?>
            </td>
        </tr>
        <tr>
            <th>氏名</th>
            <td>
                <?php echo $this->Form->input('name') ?>
            </td>
        </tr>
        <tr>
            <th>メールアドレス</th>
            <td>
                <?php echo $this->Form->input('email') ?>
            </td>
        </tr>
        <tr>
            <th>お住まい</th>
            <td>
                <?php echo $this->Form->input('address') ?>
            </td>
        </tr>
        <tr>
            <th>Twitter ID</th>
            <td><?php echo h($u['twitter_id']) ?></td>
        </tr>
        <tr>
            <th>Facebook ID</th>
            <td><?php echo h($u['facebook_id']) ?></td>
        </tr>
        <tr>
            <th>自己紹介</th>
            <td>
                <?php echo $this->Form->input('self_description') ?>
            </td>
        </tr>
        <tr>
            <th>URL1</th>
            <td>
                <?php echo $this->Form->input('url1') ?>
            </td>
        </tr>
        <tr>
            <th>URL2</th>
            <td>
                <?php echo $this->Form->input('url2') ?>
            </td>
        </tr>
        <tr>
            <th>URL3</th>
            <td>
                <?php echo $this->Form->input('url3') ?>
            </td>
        </tr>
        <tr>
            <th>リターン受け取り先住所</th>
            <td>
                <?php echo $this->Form->input('receive_address') ?>
            </td>
        </tr>
        <tr>
            <th>データ作成日時</th>
            <td><?php echo date('Y/m/d H:i', strtotime($u['created'])) ?></td>
        </tr>
        <tr>
            <th>データ更新日時</th>
            <td><?php echo date('Y/m/d H:i', strtotime($u['modified'])) ?></td>
        </tr>
    </table>
    <button type="submit" class="btn btn-primary">更新</button>
    <?php echo $this->Form->end()?>
</div>


