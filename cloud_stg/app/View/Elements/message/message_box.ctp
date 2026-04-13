<div>
    <?php if($m['Owner']['id'] == $auth_user['User']['id']): //自分がオーナー?>
        <?php echo $this->User->get_user_img_md_from_user_id($m['Backer']['id']) ?>
        <?php echo h($m['Backer']['nick_name']) ?>
    <?php else: ?>
        <?php echo $this->User->get_user_img_md_from_user_id($m['Owner']['id']) ?>
        <?php echo h($m['Owner']['nick_name']) ?>
    <?php endif ?>

    <?php if($m['MessageContent']['sender_id'] == $auth_user['User']['id']): //自分が送信?>
        送信
    <?php else: ?>
        <?php if(!$m['Message']['read_flag']): ?>
            未読
        <?php else: ?>
            未返信
        <?php endif; ?>
    <?php endif ?>

    <?php echo date('Y/m/d H:i', strtotime($m['MessageContent']['created'])) ?>

    <a href="<?php echo $this->Html->url(array(
            'action' => 'view', $m['Message']['project_id'], $m['Message']['backer_id']
    )) ?>">
        <?php echo $this->Text->truncate($m['MessageContent']['content'], 40, array(
                'exact' => true, 'html' => false
        )) ?>
    </a>
</div>