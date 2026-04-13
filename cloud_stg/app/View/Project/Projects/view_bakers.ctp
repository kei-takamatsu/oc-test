<?php echo $this->Html->css('project_view', null, array('inline' => false)) ?>

<h2 class="title"><span class="el-icon-group"> 支援者</h2>

<?php if(!empty($backers)): ?>
    <?php foreach($backers as $backer): ?>

        <div class="project_comment clearfix">
            <table style="width:100%;">
                <tr>
                    <td class="img_cell" style="width:68px;">
                        <a href="<?php echo $this->Html->url(array(
                                'controller' => 'users', 'action' => 'view', $backer['User']['id']
                        )) ?>">
                            <?php echo $this->User->get_user_img_md($backer) ?>
                        </a>
                    </td>
                    <td>
                        <div class="date_str pull-right">
                            <?php echo date('Y/m/d', strtotime($backer['BackedProject']['created'])) ?>
                        </div>
                        <div class="pull-left">
                            <?php echo $this->Html->link(h($backer['User']['nick_name']), array(
                                    'controller' => 'users', 'action' => 'view', $backer['User']['id']
                            )); ?>
                            <br>

                            <?php echo nl2br(h($backer['BackedProject']['comment'])); ?>
                        </div>
                    </td>
                </tr>
            </table>
            <hr>
        </div>
    <?php endforeach; ?>

    <?php echo $this->element('base/pagination') ?>

<?php else: ?>
    <p>支援者はまだいません。</p>
<?php endif; ?>











