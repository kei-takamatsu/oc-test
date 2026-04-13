<?php $flash = $this->Session->flash(); ?>
<?php if($flash): ?>
    <div class="flash_wrap" onclick="$(this).hide();">
        <div class="flash">
            <?php echo $flash; ?>
        </div>
    </div>
<?php endif; ?>