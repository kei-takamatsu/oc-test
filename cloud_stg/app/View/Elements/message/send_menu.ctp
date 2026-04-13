<div class="message_box_menu clearfix">
    <div class="<?php if($mode == 'backed')
        echo 'active' ?>" onclick="location.href='<?php echo $this->Html->url(array('action' => 'send_backed')) ?>'">
        <p>
            支援した
        </p>
    </div>
    <div class="<?php if($mode == 'created')
        echo 'active' ?>" onclick="location.href='<?php echo $this->Html->url(array('action' => 'send_created')) ?>'">
        <p>
            作成した
        </p>
    </div>
</div>
