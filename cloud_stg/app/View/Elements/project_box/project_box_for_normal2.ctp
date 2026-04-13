                            <a href="<?php echo $this->Html->url('/projects/view/'.$project['Project']['id']) ?>" class="cplt_box">
                                <div class="p_thumbnail">
                                    <figure class="p_thumbnail_wrap">
                                        <?php echo $this->element('project_box/inner/_project_box_img2', array('project' => $project)) ?>
                                    </figure>
                                </div>
                                <div class="p_card">
                                    <div class="card_ttl">
                                        <?php echo $this->element('project_box/inner/_project_box_string_head2', array('project' => $project)) ?>
                                    </div>
                                    <div class="box">
                                        <div class="box_money">
                                        <?php echo $this->element('project_box/inner/_project_box_footer2', array('project' => $project)) ?>
                                        </div>
                                        <?php echo $this->element('project/graph2', array('project' => $project)) ?>
                                    </div>
                                </div>
    <?php if($this->Project->get_backed_per($project) >= 100): ?>
        <div class="project_success"> SUCCESS!</div>
    <?php endif ?>
                            </a>