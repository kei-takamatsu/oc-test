</div>
<div id="back_top" onclick="back_top();">
    <?php echo $this->Html->image('back_top.png') ?>
</div>
</div>

<div id="loader" style="display: none;">
    <div id="loader_content">
        <?php echo $this->Html->image('loader.gif') ?>
    </div>
</div>

<?php echo $this->Html->script('jquery-2.1.0.min') ?>
<?php echo $this->Html->script('anime') ?>
<?php echo $this->Html->script('/bootstrap/js/bootstrap.min') ?>
<?php echo $this->Html->script('default_admin') ?>
<?php echo $this->fetch('script') ?>
</body>
</html>