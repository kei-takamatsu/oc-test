<div class="container">
    <div class="clearfix">
        <?php echo $this->element('project/project_header', array(
                'project' => $project, 'mode' => $mode
        )) ?>
    </div>
    <div class="col-md-7 clearfix">
        <?php echo $this->element('project/project_thumbnail', array(
                'project' => $project, 'mode' => $mode
        )) ?>
        <?php foreach($contents as $c): ?>
            <?php $c = $c['ProjectContent'] ?>
            <?php if($c['type'] == 'text'): ?>
                <p><?php echo nl2br(h($c['txt_content'])) ?></p>
            <?php elseif($c['type'] == 'midashi'): ?>
                <h2><?php echo h($c['txt_content']) ?></h2>
            <?php elseif($c['type'] == 'img'): ?>
                <div style="margin:25px 0;">
                    <?php echo $this->Label->image($c['img'], array('class' => 'img-responsive')) ?>
                </div>
            <?php else: ?>
                <div style="margin:25px 0;">
                    <iframe class="youtube" src="//www.youtube.com/embed/<?php echo h($c['youtube_code']) ?>"
                            frameborder="0" allowfullscreen></iframe>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>

        <?php if($auth): ?>
            <h3>コメントの投稿</h3>
            <?php echo $this->Form->create('Comment', array(
                    'url' => array(
                            'controller' => 'comments', 'action' => 'add'
                    ), 'inputDefaults' => array('class' => 'form-control')
            )); ?>

            <?php echo $this->Form->input('user_id', array(
                    'type' => 'hidden', 'value' => AuthComponent::user('id')
            )); ?>
            <?php echo $this->Form->input('project_id', array(
                    'type' => 'hidden', 'value' => $project['Project']['id']
            )); ?>
            <div class="form-group">
                <?php echo $this->Form->input('comment', array(
                        'type' => 'textarea', 'label' => false, 'required' => 'required'
                )); ?>
            </div>
            <?php echo $this->Form->submit('投稿', array(
                    'div' => 'controls', 'class' => 'btn btn-primary col-xs-4'
            )); ?>
            <?php echo $this->Form->end(); ?>
        <?php endif; ?>
        <br><br><br>
    </div>

    <div class="col-md-5">
        <?php echo $this->element('project/project_info', array('project' => $project)) ?>
    </div>
</div>
<br>

<?php $this->start('script') ?>
<script>
    $(document).ready(function () {
        $('*').click(function () {
            return false;
        });
    });
</script>
<?php $this->end() ?>




