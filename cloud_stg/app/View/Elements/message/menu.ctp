<div class="sub_menu_wrap">
    <div class="sub_menu <?php if($mode == 'box')
        echo 'active' ?>" onclick="location.href='<?php echo $this->Html->url(array('action' => 'index')) ?>';">
        メッセージBOX
    </div>
    <div class="sub_menu <?php if($mode == 'send')
        echo 'active' ?>" onclick="location.href='<?php echo $this->Html->url(array('action' => 'send_backed')) ?>';">
        メッセージを送る
    </div>
</div>