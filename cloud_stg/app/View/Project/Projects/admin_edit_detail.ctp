<?php echo $this->element('admin/admin_project_menu') ?>

<div class="btn-group">
    <input type="button" onClick="new_midashi();" value="見出し" class="btn btn-default">
    <input type="button" onClick="new_text();" value="テキスト" class="btn btn-default">
    <input type="button" onClick="new_img();" value="画像" class="btn btn-default">
    <input type="button" onClick="new_movie();" value="動画" class="btn btn-default">
    <input type="button"
           onClick="var w = window.open(); w.location.href='<?php echo $this->Html->url(array(
                   'action' => 'admin_view', $project['Project']['id']
           )) ?>';"
           value="プレビュー" class="btn btn-default">
</div>

<div id="new_midashi" class="none">
    <br>
    <h4>見出しの追加</h4><br>
    <?php echo $this->Form->create('ProjectContent', array(
            'inputDefaults' => array(
                    'label' => false, 'class' => 'form-control'
            ), 'url' => array(
                    'controller' => 'project_contents', 'action' => 'admin_add', $project['Project']['id']
            )
    )) ?>
    <div class="form-group">
        <?php echo $this->Form->input('txt_content', array('type' => 'text')); ?>
    </div>

    <div class="form-group">
        <?php echo $this->Form->submit('追加', array('class' => 'btn btn-success col-xs-3')) ?>
    </div>
    <?php echo $this->Form->hidden('type', array('value' => 'midashi')) ?>
    <?php echo $this->Form->end(); ?>
    <br><br>
</div>

<div id="new_text" class="none">
    <br>
    <h4>テキストの追加</h4><br>
    <?php echo $this->Form->create('ProjectContent', array(
            'inputDefaults' => array(
                    'label' => false, 'class' => 'form-control'
            ), 'url' => array(
                    'controller' => 'project_contents', 'action' => 'admin_add', $project['Project']['id']
            )
    )) ?>
    <div class="form-group">
        <?php echo $this->Form->input('txt_content', array(
                'type' => 'textarea', 'rows' => 10,
        )); ?>
    </div>

    <div class="form-group">
        <?php echo $this->Form->submit('追加', array('class' => 'btn btn-success col-xs-3')) ?>
    </div>
    <?php echo $this->Form->hidden('type', array('value' => 'text')) ?>
    <?php echo $this->Form->end(); ?>
    <br><br>
</div>

<div id="new_img" class="none">
    <br>
    <h4>画像の追加</h4><br>
    <?php echo $this->Form->create('ProjectContent', array(
            'type' => 'file', 'inputDefaults' => array(
                    'label' => false, 'class' => 'form-control'
            ), 'url' => array(
                    'controller' => 'project_contents', 'action' => 'admin_add', $project['Project']['id']
            )
    )) ?>
    <div class="form-group">
        <?php echo $this->Form->input('img', array('type' => 'file')); ?>
    </div>
    <div class="form-group">
        <?php echo $this->Form->submit('追加', array('class' => 'btn btn-success col-xs-3')) ?>
    </div>
    <?php echo $this->Form->hidden('type', array('value' => 'img')) ?>
    <?php echo $this->Form->end(); ?>
    <br><br>
</div>

<div id="new_movie" class="none">
    <br>
    <h4>動画の追加</h4><br>
    <?php echo $this->Form->create('ProjectContent', array(
            'inputDefaults' => array(
                    'label' => false, 'class' => 'form-control'
            ), 'url' => array(
                    'controller' => 'project_contents', 'action' => 'admin_add', $project['Project']['id']
            )
    )) ?>

    <div class="form-group">
        <?php echo $this->Form->input('youtube_code', array(
                'label' => 'Youtube URL(Youtube コード)', 'onChange' => 'youtube_code($(this));'
        )); ?>
    </div>

    <div class="form-group">
        <?php echo $this->Form->submit('追加', array('class' => 'btn btn-success col-xs-3')) ?>
    </div>

    <?php echo $this->Form->hidden('type', array('value' => 'movie')) ?>
    <?php echo $this->Form->end(); ?>
    <br><br>
</div>

<div style="margin-top:20px;">
    <?php foreach($project_contents as $idx => $c): ?>
        <?php echo $this->Form->create('ProjectContent', array(
                'url' => array(
                        'controller' => 'project_contents', 'action' => 'admin_edit', $c['ProjectContent']['id']
                ), 'type' => 'file', 'inputDefaults' => array(
                        'label' => false, 'div' => false, 'class' => 'form-control'
                )
        )) ?>
        <table class="table">
            <tr style="background-color: #ccc;">
                <td>
                    <?php echo h($c['ProjectContent']['order']) ?>
                <td>
                    <input type="submit" value="更新" class="btn btn-primary"/>
                    <input type="button"
                           onClick="if(window.confirm('削除しますか？')){location.href='<?php echo $this->Html->url(array(
                                   'controller' => 'project_contents', 'action' => 'admin_delete',
                                   $c['ProjectContent']['id']
                           )) ?>';}"
                           class="btn btn-primary" value="削除"/>
                    <input type="button"
                           onClick="location.href='<?php echo $this->Html->url(array(
                                   'controller' => 'project_contents', 'action' => 'admin_order_mae',
                                   $c['ProjectContent']['project_id'], $c['ProjectContent']['order']
                           )) ?>'"
                           value="前" class="btn btn-success"/>
                    <input type="button"
                           onClick="location.href='<?php echo $this->Html->url(array(
                                   'controller' => 'project_contents', 'action' => 'admin_order_ato',
                                   $c['ProjectContent']['project_id'], $c['ProjectContent']['order']
                           )) ?>'"
                           value="後" class="btn btn-success"/>
                </td>
            </tr>
            <tr>
                <td colspan="5">
                    <?php if($c['ProjectContent']['type'] == 'text'): ?>
                        <?php echo $this->Form->input('txt_content', array(
                                'type' => 'textarea', 'value' => $c['ProjectContent']['txt_content']
                        )); ?>

                    <?php elseif($c['ProjectContent']['type'] == 'midashi'): ?>
                        <?php echo $this->Form->input('txt_content', array(
                                'type' => 'text', 'value' => $c['ProjectContent']['txt_content'],
                                'class' => 'form-control midashi'
                        )); ?>

                    <?php elseif($c['ProjectContent']['type'] == 'img'): ?>
                        <div class="form-group">
                            <?php echo $this->Form->input('img', array('type' => 'file')); ?>
                        </div>
                        <?php if($this->Label->link($c['ProjectContent']['img'])): ?>
                            <?php echo $this->Label->image($c['ProjectContent']['img'], array('class' => 'img-responsive')) ?>
                        <?php endif; ?>

                    <?php else: ?>
                        <?php echo $this->Form->input('youtube_code', array(
                                'id' => 'code', 'onChange' => 'youtube_code($(this))',
                                'value' => $c['ProjectContent']['youtube_code'], 'label' => 'Youtube URL(コード)'
                        )); ?>
                        <br>
                        <div class="clearfix">
                            <iframe class="youtube"
                                    src="//www.youtube.com/embed/<?php echo h($c['ProjectContent']['youtube_code']) ?>"
                                    frameborder="0" allowfullscreen></iframe>
                        </div>
                    <?php endif; ?>
                </td>
                <?php echo $this->Form->hidden('id', array('value' => $c['ProjectContent']['id'])) ?>
            </tr>
        </table>
        <?php echo $this->Form->end() ?>
    <?php endforeach; ?>
</div>

<?php $this->start('script') ?>
<script>
    function new_midashi() {
        $('#new_midashi').toggleClass('none');
        $('#new_text').addClass('none');
        $('#new_img').addClass('none');
        $('#new_movie').addClass('none');
    }

    function new_text() {
        $('#new_midashi').addClass('none');
        $('#new_text').toggleClass('none');
        $('#new_img').addClass('none');
        $('#new_movie').addClass('none');
    }

    function new_img() {
        $('#new_midashi').addClass('none');
        $('#new_text').addClass('none');
        $('#new_img').toggleClass('none');
        $('#new_movie').addClass('none');
    }

    function new_movie() {
        $('#new_midashi').addClass('none');
        $('#new_text').addClass('none');
        $('#new_img').addClass('none');
        $('#new_movie').toggleClass('none');
    }

    function youtube_code(t) {
        var code = t.val();
        var re = /http[s]?:\/\/www\.youtube\.com\/watch\?v=(.+)/;
        var match = re.exec(code);
        if (match) {
            code = match[1];
            t.val(code);
        } else {
            re = /http[s]?:/;
            match = re.exec(code);
            if (match) {
                alert('正しいYouTubeのURLかコードを入力してください。');
            }
        }
    }

    window.onresize = function () {
        resize_youtube();
    };

    function resize_youtube() {
        $('.youtube').css('height', $('.youtube').width() * 430 / 710);
    }

    $(document).ready(function () {
        resize_youtube();
    });
</script>
<?php $this->end(); ?>
