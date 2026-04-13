<?php foreach($project_contents as $idx => $c): ?>
    <?php echo $this->Form->create('ProjectContent', array(
            'url' => array(
                    'controller' => 'project_contents', 'action' => 'edit', $c['ProjectContent']['id']
            ), 'type' => 'file', 'inputDefaults' => array(
                    'label' => false, 'div' => false, 'class' => 'form-control'
            )
    )) ?>
    <table class="table">
        <tr style="background-color: #ccc;">
            <td>
                <?php echo h($c['ProjectContent']['order']) ?>
            <td>
                <input type="submit" value="更新" class="btn btn-success btn-sm"/>
                <input type="button"
                       onClick="if(window.confirm('削除しますか？')){location.href='<?php echo $this->Html->url(array(
                               'controller' => 'project_contents', 'action' => 'delete', $c['ProjectContent']['id']
                       )) ?>';}"
                       class="btn btn-danger btn-sm" value="削除"/>
                <input type="button"
                       onClick="location.href='<?php echo $this->Html->url(array(
                               'controller' => 'project_contents', 'action' => 'order_mae',
                               $c['ProjectContent']['project_id'], $c['ProjectContent']['order']
                       )) ?>'"
                       value="前" class="btn btn-primary btn-sm"/>
                <input type="button"
                       onClick="location.href='<?php echo $this->Html->url(array(
                               'controller' => 'project_contents', 'action' => 'order_ato',
                               $c['ProjectContent']['project_id'], $c['ProjectContent']['order']
                       )) ?>'"
                       value="後" class="btn btn-primary btn-sm"/>
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