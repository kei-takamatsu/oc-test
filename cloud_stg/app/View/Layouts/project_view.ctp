<?php echo $this->element('base/header') ?>
<?php echo $this->element('base/menu') ?>
<?php echo $this->element('base/flash'); ?>
<div class="top_back"></div>


<div class="project clearfix">
    <div class="project_header">
        <?php echo $this->element('project/project_header') ?>
    </div>
    <div class="project_content clearfix">
        <div class="left">
            <?php echo $this->fetch('content'); ?>
        </div>
        <div class="right">
            <?php echo $this->element('project/project_info') ?>
            <?php echo $this->element('project/backing_levels', array('project' => $project)) ?>
        </div>
    </div>
</div>


<?php echo $this->element('base/footer') ?>
<?php //echo $this->element('sql_dump');?>

