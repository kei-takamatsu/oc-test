<div class="bread">
    <?php echo $this->Html->link('ユーザ一覧', '/admin/admin_users/') ?> &gt;
    ユーザー詳細
</div>
<div class="setting_title">
    <h2>ユーザー詳細</h2>
</div>
<br><br>

<div class="container">
    <table class="table table-bordered">
        <tr>
            <th style="width:140px;">id</th>
            <td><?php echo h($u['id']) ?></td>
        </tr>
        <tr>
            <th>ユーザーネーム</th>
            <td><?php echo h($u['nick_name']) ?></td>
        </tr>
        <tr>
            <th>氏名</th>
            <td><?php echo h($u['name']) ?></td>
        </tr>
        <tr>
            <th>メールアドレス</th>
            <td><?php echo h($u['email']) ?></td>
        </tr>
        <tr>
            <th>お住まい</th>
            <td><?php echo h($u['address']) ?></td>
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
            <td><?php echo nl2br(h($u['self_description'])) ?></td>
        </tr>
        <tr>
            <th>URL1</th>
            <td>
                <a href="<?php echo h($u['url1']) ?>" target="_blank">
                    <?php echo h($u['url1']) ?>
                </a>
            </td>
        </tr>
        <tr>
            <th>URL2</th>
            <td>
                <a href="<?php echo h($u['url2']) ?>" target="_blank">
                    <?php echo h($u['url2']) ?>
                </a>
            </td>
        </tr>
        <tr>
            <th>URL3</th>
            <td>
                <a href="<?php echo h($u['url3']) ?>" target="_blank">
                    <?php echo h($u['url3']) ?>
                </a>
            </td>
        </tr>
        <tr>
            <th>リターン受け取り先住所</th>
            <td><?php echo h($u['receive_address']) ?></td>
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
</div>


