<?php echo $this->Html->css('sp/project_view', null, array('inline' => false)) ?>
<?php echo $this->Html->script('movie_resize', array('inline' => false)) ?>

<div class="tab_xs_header">
    <div class="menu menu_back" onclick="location.href='<?php echo $this->Html->url(array(
            'action' => 'view', $project['Project']['id'], 'report'
    )) ?>';">
        <span class="el-icon-chevron-left"></span>
        活動報告
    </div>
</div>

<div class="project">
    <div class="report_detail">
        <div class="report_title">
            <h1>
                <?php echo h($report['Report']['title']) ?>
            </h1>
        </div>
        <div class="report_date">
            <?php echo date('Y年m月d日', strtotime($report['Report']['created'])) ?>
        </div>
        <?php if($this->Label->url($report['Report']['thumbnail'])){
            echo $this->Label->image($report['Report']['thumbnail'], array('class' => 'img-responsive'));
        } ?>

        <?php if($report_contents): ?>
            <?php foreach($report_contents as $c): ?>
                <?php $c = $c['ReportContent'] ?>

                <?php if($c['type'] == 'text'): ?>
                    <div class="text_content">
                        <?php echo nl2br($c['txt_content']) ?>
                    </div>

                <?php elseif($c['type'] == 'img'): ?>
                    <div class="img_content">
                        <?php echo $this->Label->image($c['img'], array('class' => 'img-responsive')) ?>
                    </div>
                <?php else: ?>
                    <div class="movie_content">
                        <?php if($c['movie_type'] == 'youtube'): ?>
                            <iframe class="movie" src="//www.youtube.com/embed/<?php echo h($c['movie_code']) ?>"
                                    frameborder="0" allowfullscreen></iframe>
                        <?php else: ?>
                            <iframe class="movie"
                                    src="//player.vimeo.com/video/<?php echo h($c['movie_code']) ?>?title=0&amp;byline=0&amp;portrait=0&amp;color=9c1e1e"
                                    frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>


<?php $this->start('script') ?>
<script>
    $(document).ready(function () {
        resize_movie(750);
    });
</script>
<?php $this->end() ?>
