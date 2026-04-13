<?php echo $this->Html->css('sp/project_view', null, array('inline' => false)) ?>

<?php echo $this->element('project/sp/tab_header_back') ?>
<div class="project">
    <h3><span class="el-icon-group"></span> 支援者</h3>
    <?php if(!empty($backers)): ?>
        <br>
        <?php foreach($backers as $backer): ?>

            <div class="project_comment clearfix">
                <table>
                    <tr>
                        <td class="img_cell">
                            <a href="<?php echo $this->Html->url(array(
                                    'controller' => 'users', 'action' => 'view', $backer['User']['id']
                            )) ?>">
                                <?php echo $this->User->get_user_img_md($backer) ?>
                            </a>
                        </td>
                        <td>
							<span class="date_str">
								<?php echo date('Y/m/d', strtotime($backer['BackedProject']['created'])) ?>
							</span>
                            <br>
                            <?php echo $this->Html->link(h($backer['User']['nick_name']), array(
                                    'controller' => 'users', 'action' => 'view', $backer['User']['id']
                            )); ?>
                            <br>

                            <?php echo nl2br(h($backer['BackedProject']['comment'])); ?>
                        </td>
                    </tr>
                </table>
            </div>
            <hr>
        <?php endforeach; ?>
        <div style="padding:0 10px;">
            <?php echo $this->element('base/pagination') ?>
        </div>

    <?php else: ?>
        <div style="padding:20px 10px;">
            <p>支援者はまだいません。</p>
        </div>
    <?php endif; ?>
</div>






