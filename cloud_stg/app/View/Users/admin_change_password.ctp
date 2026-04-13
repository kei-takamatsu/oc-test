<div class="row-fluid">
    <div class="span12">
        <h2>パスワード変更</h2>
        <?php echo $this->Form->create('User', array('inputDefaults' => array('label' => false))); ?>
        <table class="table">
            <tr>
                <th>新しいパスワード</th>
                <td>
                    <?php
                    echo $this->Form->input('id', array(
                            'type' => 'hidden', 'value' => $user
                    ));
                    echo $this->Form->input('password');
                    echo $this->Form->input('password2', array('type' => 'password'));
                    ?>
                </td>
            </tr>
        </table>
        <?php echo $this->Form->submit(__('変更'), array('class' => 'btn btn-warning col-xs-3')); ?>
        <?php echo $this->Form->end(); ?>
    </div>
</div>