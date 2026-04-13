<div class="left">
    <div class="user_img">
        <?php echo $this->User->get_user_img($user) ?>
    </div>
    <div class="name">
        <p class="profile_nick_name">
            <?php echo h($user['User']['nick_name']) ?>
        </p>
        <p class="profile_address">
            <?php if(!empty($user['User']['address'])): ?>
                <span class="el-icon-map-marker"></span>
                <?php echo h($user['User']['address']) ?>
            <?php endif; ?>
        </p>
    </div>
</div>
<div class="right">
    <?php echo nl2br(h($user['User']['self_description'])) ?>

    <p class="profile_url">
        <?php if($user['User']['url1']): ?>
            <span class="el-icon-link"></span>
            <a href="<?php echo h($user['User']['url1']) ?>"
               target="_blank"><?php echo h($user['User']['url1']) ?>
            </a><br>
        <?php endif; ?>

        <?php if($user['User']['url2']): ?>
            <span class="el-icon-link"></span>
            <a href="<?php echo h($user['User']['url2']) ?>"
               target="_blank"><?php echo h($user['User']['url2']) ?>
            </a><br>
        <?php endif; ?>

        <?php if($user['User']['url3']): ?>
            <span class="el-icon-link"></span>
            <a href="<?php echo h($user['User']['url3']) ?>"
               target="_blank"><?php echo h($user['User']['url3']) ?>
            </a><br>
        <?php endif; ?>

        <?php if($user['User']['id'] != $auth_user['User']['id']): ?>
            <br>
            <a href="<?php echo $this->Html->url('/messages/view/'.h($user['User']['id'])) ?>" class="btn btn-info">メッセージを送る</a>
        <?php endif ?>
    </p>
</div>

