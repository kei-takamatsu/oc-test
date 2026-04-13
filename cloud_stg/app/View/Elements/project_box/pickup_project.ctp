        <?php if($this->Label->url_thumb($project['Project']['pic'], 400, 267)): ?>
            <a href="<?php echo $this->Html->url('/projects/view/'.$project['Project']['id']) ?>" class="top_featured">
                                <div class="cover_border"></div>
                <div class="top_featured_inner">
                <?php echo $this->Label->image_thumb($project['Project']['pic'], 400, 267, array('class' => 'img img-responsive')) ?>
                </div>
                                <div class="tf_fr_detail">
                    <h2 class="tf_fr_ttl">
                        <?php echo h($project['Project']['project_name']); ?>
                    </h2>
                    <div class="top_box">
                        <div class="top_box_money">
                            ￥<?php echo number_format(h($project['Project']['collected_amount'])); ?><span class="top_remain">[&nbsp;残り：<?php echo $this->Project->get_zan_day($project); ?>&nbsp;]</span>
                        </div>
<?php echo $this->element('project/graph', array('project' => $project)) ?>
                    </div>
                </div>
            </a>
        <?php endif; ?>
        <?php if($this->Project->get_backed_per($project) >= 100): ?>
            <div class="project_success"> SUCCESS!</div>
        <?php endif ?>
