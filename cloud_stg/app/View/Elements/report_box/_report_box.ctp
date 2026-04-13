
                        <a href="<?php echo $this->Html->url(array( 'controller' => 'projects', 'action' => 'view', $report['Report']['project_id'], 'report_view', $report['Report']['id'] )) ?>e" class="tr_box">

                            <div class="trb_thum">


        <?php echo $this->element('report_box/inner/_project_box_img2', compact('report')) ?>

        <?php if(empty($opened)){
            $opened = null;
        } ?>
                            </div>
            <?php echo $this->element('report_box/inner/_project_box_string_head2', array(
                    'report' => $report, 'opened' => $opened
            )) ?>
</a>