<div class="project_menu_tab">
    <div>
        <a href="<?php echo $this->Html->url('/projects/view/'.$project['Project']['id']) ?>">
            <span class="el-icon-smiley"></span>
            詳細
        </a>
    </div>
    <div>
        <a href="<?php echo $this->Html->url('/projects/view/'.$project['Project']['id'].'/comment') ?>">
            <span class="el-icon-comment"></span>
            コメント
        </a>
    </div>
    <div>
        <a href="<?php echo $this->Html->url('/projects/view/'.$project['Project']['id'].'/backers') ?>">
            <span class="el-icon-gift"></span>
            支援
        </a>
    </div>
    <div>
        <a href="<?php echo $this->Html->url('/projects/view/'.$project['Project']['id'].'/report') ?>">
            <span class="el-icon-bullhorn"></span>
            活動
        </a>
    </div>
</div>
