<?php $per = $this->Project->get_backed_per($project); ?>
<!--
<div class="progress">
    <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="<?php echo $per ?>"
         aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($per > 100) ? 100 : $per ?>%;">
        <?php echo $per ?>%
    </div>
</div>
-->
                                    <div class="top_box_bar_wrap_pu">
                                        <div class="top_box_bar_pu">

                                            <span role="progressbar" aria-valuenow="<?php echo $per ?>"
         aria-valuemin="0" aria-valuemax="100"  class="bar_<?php echo ($per > 100) ? 100 : $per ?>"></span>
                                        </div>
                                        <div class="top_box_bar_num_pu">
                                            <?php echo $per ?>%
                                        </div>
                                    </div>
