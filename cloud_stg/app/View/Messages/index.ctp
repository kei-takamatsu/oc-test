<?php echo $this->Html->css('mypage', null, array('inline' => false)) ?>

<?php //echo $this->element('message/menu', array('mode' => 'box'))?>

<h4><span class="el-icon-envelope"></span> メッセージBOX</h4>

<div class="message_box_menu clearfix">
    <div class="active">
        <p>
            すべて
        </p>
    </div>
    <div>
        <p>
            <?php echo $this->Html->link('未返信', array('action' => 'no_reply')) ?>
        </p>
    </div>
</div>

<div class="container">
    <table class="table table-bordered">
        <!--		<tr>-->
        <!--			<th>相手</th>-->
        <!--			<th>受信・送信</th>-->
        <!--			<th>日時</th>-->
        <!--			<th></th>-->
        <!--		</tr>-->
        <?php foreach($messages as $m): ?>
            <?php //echo $this->element('message/message_box', compact('m'))?>

            <?php if($m['Receiver']['id'] == $auth_user['User']['id'] && !$m['MessagePair']['read_flag']): ?>
                <tr class="no_read">
            <?php else: ?>
                <tr class="">
            <?php endif ?>
            <td>
                <?php
                if($m['Receiver']['id'] == $auth_user['User']['id']){
                    echo '<a href="'.$this->Html->url('/profile/'.$m['Sender']['id']).'">';
                    echo $this->User->get_user_img_md_from_user_id($m['Sender']['id'], 20)."&nbsp";
                    echo h($m['Sender']['nick_name']);
                    echo '</a>';
                }else{
                    echo '<a href="'.$this->Html->url('/profile/'.$m['Receiver']['id']).'">';
                    echo $this->User->get_user_img_md_from_user_id($m['Receiver']['id'], 20)."&nbsp";
                    echo h($m['Receiver']['nick_name']);
                    echo '</a>';
                }
                ?>
            </td>
            <td>
                <?php
                if($m['MessagePair']['last_sender_id'] == $auth_user['User']['id']){
                    echo '送信';
                }else{
                    echo '受信';
                }
                ?>
            </td>
            <td>
                <?php echo date('Y年m月d日 H:i', strtotime(h($m['MessagePair']['modified']))) ?>
            </td>
            <td>
                <?php if($m['MessagePair']['last_sender_id'] == $auth_user['User']['id']): ?>
                    <a href="<?php echo $this->Html->url('/messages/view/'.$m['Receiver']['id']) ?>"
                       class="btn btn-primary btn-xs">詳細</a>
                <?php else: ?>
                    <a href="<?php echo $this->Html->url('/messages/view/'.$m['Sender']['id']) ?>"
                       class="btn btn-primary btn-xs">詳細</a>
                <?php endif ?>

            </td>
            </tr>
        <?php endforeach ?>
    </table>
    <?php echo $this->element('base/pagination') ?>
</div>


