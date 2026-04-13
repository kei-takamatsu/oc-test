<div class="mypage_sub_menu">
    <button type="button" class="btn btn-<?php echo ($menu_mode == 'backed') ? 'primary' : 'default' ?>"
            onclick="location.href='<?php echo $this->Html->url('/profile/'.$user['User']['id']) ?>'">
        <span class="el-icon-gift"></span>
        支援した
    </button>
    <button type="button" class="btn btn-<?php echo ($menu_mode == 'registered') ? 'primary' : 'default' ?>"
            onclick="location.href='<?php echo $this->Html->url('/profile/'.$user['User']['id'].'/registered') ?>'">
        <span class="el-icon-file-new"></span>
        作成した
    </button>
</div>
