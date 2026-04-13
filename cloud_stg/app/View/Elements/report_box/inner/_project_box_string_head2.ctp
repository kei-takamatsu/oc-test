


                            <div class="trb_cont">
                                <div class="trb_ttl">
                                     <?php echo h($report['Report']['title']); ?>
                                </div>
                                <p class="trb_date">
                                     <?php echo date('Y.m.d', strtotime($report['Report']['created'])) ?>
                                </p>
                            </div>

        <?php if(empty($opened)): ?>
            <div class="project_name" style="display:none;">
                <?php echo h($report['Project']['project_name']) ?>
            </div>
        <?php endif ?>