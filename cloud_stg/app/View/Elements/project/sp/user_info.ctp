<div class="user_info_header clearfix">
    <div class="left">
        <a href="<?php echo $this->Html->url(array(
                'controller' => 'users', 'action' => 'view', $project['User']['User']['id']
        )) ?>">
            <?php echo $this->User->get_user_img($project['User'], 70) ?>
        </a>
    </div>
    <div class="right">
        <div class="name">
            <a href="<?php echo $this->Html->url(array(
                    'controller' => 'users', 'action' => 'view', $project['User']['User']['id']
            )) ?>">
                <?php echo h($project['User']['User']['nick_name']) ?>
            </a>
        </div>
        <div class="address">
            <?php if(!empty($project['User']['User']['address'])): ?>
                <span class="el-icon-map-marker"></span>
                <?php echo h($project['User']['User']['address']) ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="url">
    <?php if($project['User']['User']['url1']): ?>
        <span class="el-icon-link"></span>
        <a href="<?php echo h($project['User']['User']['url1']) ?>"
           target="_blank"><?php echo h($project['User']['User']['url1']) ?>
        </a><br>
    <?php endif; ?>

    <?php if($project['User']['User']['url2']): ?>
        <span class="el-icon-link"></span>
        <a href="<?php echo h($project['User']['User']['url2']) ?>"
           target="_blank"><?php echo h($project['User']['User']['url2']) ?>
        </a><br>
    <?php endif; ?>

    <?php if($project['User']['User']['url3']): ?>
        <span class="el-icon-link"></span>
        <a href="<?php echo h($project['User']['User']['url3']) ?>"
           target="_blank"><?php echo h($project['User']['User']['url3']) ?>
        </a><br>
    <?php endif; ?>
</div>
<div class="self_description">
    <?php echo nl2br(h($project['User']['User']['self_description'])) ?>
</div>
