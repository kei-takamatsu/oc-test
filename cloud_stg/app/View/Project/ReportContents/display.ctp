<?php foreach($report_contents as $idx => $c): ?>
    <?php echo $this->Form->create('ReportContent', array(
            'url' => array(
                    'controller' => 'report_contents', 'action' => 'edit', $c['ReportContent']['id']
            ), 'type' => 'file', 'inputDefaults' => array(
                    'label' => false, 'div' => false, 'class' => 'form-control'
            )
    )) ?>
    <table class="table">
        <tr style="background-color: #ccc;">
            <td>
                <?php echo h($c['ReportContent']['order']) ?>
            <td>
                <input type="submit" value="更新" class="btn btn-success btn-sm"/>
                <input type="button"
                       onClick="if(window.confirm('削除しますか？')){location.href='<?php echo $this->Html->url(array(
                               'controller' => 'report_contents', 'action' => 'delete', $c['ReportContent']['id']
                       )) ?>';}"
                       class="btn btn-danger btn-sm" value="削除"/>
                <input type="button"
                       onClick="location.href='<?php echo $this->Html->url(array(
                               'controller' => 'report_contents', 'action' => 'order_mae',
                               $c['ReportContent']['report_id'], $c['ReportContent']['order']
                       )) ?>'"
                       value="前" class="btn btn-primary btn-sm"/>
                <input type="button"
                       onClick="location.href='<?php echo $this->Html->url(array(
                               'controller' => 'report_contents', 'action' => 'order_ato',
                               $c['ReportContent']['report_id'], $c['ReportContent']['order']
                       )) ?>'"
                       value="後" class="btn btn-primary btn-sm"/>
            </td>
        </tr>
        <tr>
            <td colspan="5">
                <?php if($c['ReportContent']['type'] == 'text'): ?>
                    <?php echo $this->Form->input('txt_content', array(
                            'type' => 'textarea', 'value' => $c['ReportContent']['txt_content']
                    )); ?>

                <?php elseif($c['ReportContent']['type'] == 'midashi'): ?>
                    <?php echo $this->Form->input('txt_content', array(
                            'type' => 'text', 'value' => $c['ReportContent']['txt_content'],
                            'class' => 'form-control midashi'
                    )); ?>

                <?php elseif($c['ReportContent']['type'] == 'img'): ?>
                    <div class="form-group">
                        <?php echo $this->Form->input('img', array('type' => 'file')); ?>
                    </div>
                    <?php if($this->Label->link($c['ReportContent']['img'])): ?>
                        <?php echo $this->Label->image($c['ReportContent']['img'], array('class' => 'img-responsive')) ?>
                    <?php endif; ?>

                <?php else: ?>
                    <?php echo $this->Form->input('youtube_code', array(
                            'id' => 'code', 'onChange' => 'youtube_code($(this))',
                            'value' => $c['ReportContent']['youtube_code'], 'label' => 'Youtube URL(コード)'
                    )); ?>
                    <br>
                    <div class="clearfix">
                        <iframe class="youtube"
                                src="//www.youtube.com/embed/<?php echo h($c['ReportContent']['youtube_code']) ?>"
                                frameborder="0" allowfullscreen></iframe>
                    </div>
                <?php endif; ?>
            </td>
            <?php echo $this->Form->hidden('id', array('value' => $c['ReportContent']['id'])) ?>
        </tr>
    </table>
    <?php echo $this->Form->end() ?>
<?php endforeach; ?>