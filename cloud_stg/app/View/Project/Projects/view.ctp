<?php echo $this->Html->css('project_view', null, array('inline' => false)) ?>

<?php $this->start('ogp') ?>
    <meta property="og:title" content="<?php echo h($project['Project']['project_name']) ?>"/>
    <meta property="og:type" content="article"/>
    <meta property="og:url" content="<?php echo $this->Html->url(null, true) ?>"/>
    <meta property="og:image"
          content="<?php echo ($this->Label->link($project['Project']['pic'])) ? $this->Label->url($project['Project']['pic'], true) : '' ?>"/>
    <meta property="og:site_name" content="<?php echo h($setting['site_name']) ?>"/>
    <meta property="og:description" content="<?php echo h($project['Project']['description']) ?>"/>
<?php $this->end() ?>

<?php echo $this->element('project/project_thumbnail', array(
        'project' => $project, 'mode' => $mode
)) ?>
<?php echo $this->element('project/social_btn', array('project' => $project)) ?>
<?php echo $this->element('project/project_detail', array(
        'project' => $project, 'mode' => $mode
)) ?>
aaaaaaaaaaaaaaa
<?php $this->start('script') ?>
    <script>
        $(document).ready(function () {
            resize_movie(750);
        });
    </script>
<?php $this->end() ?>