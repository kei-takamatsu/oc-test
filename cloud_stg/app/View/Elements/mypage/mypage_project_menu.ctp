<div class="sub_menu_wrap">
    <div class="sub_menu <?php echo ($mode == 'backed') ? 'active' : '' ?>"
         onclick="location.href='<?php echo $this->Html->url('/mypage') ?>';">
        支援した
    </div>
    <div class="sub_menu <?php echo ($mode == 'registered') ? 'active' : '' ?>"
         onclick="location.href='<?php echo $this->Html->url('/projects/registered') ?>';">
        作成した
    </div>
    <div class="sub_menu report_menu <?php echo ($mode == 'report') ? 'active' : '' ?>" onclick="report_menu_toggle();">
        活動報告 <b class="caret"></b>
    </div>
    <div class="report_sub_menu" style="display: none;">
        <div onclick="location.href='<?php echo $this->Html->url('/reports') ?>';">
            活動報告一覧
        </div>
        <div onclick="location.href='<?php echo $this->Html->url('/reports/add') ?>';">
            活動報告追加
        </div>
    </div>
</div>

<?php $this->start('script') ?>
<script>
    $('body').on('click', function (e) {
        if (!$(e.target).is('.report_menu')) {
            if (!$(e.target).is('.report_sub_menu') && !$(e.target).closest('.report_sub_menu').size()) {
                if ($('.report_sub_menu').is(':visible')) {
                    $('.report_sub_menu').slideUp(50);
                }
            }
        }
    });

    function report_menu_toggle() {
        $('.report_sub_menu').slideToggle(100);
    }
</script>
<?php $this->end() ?>