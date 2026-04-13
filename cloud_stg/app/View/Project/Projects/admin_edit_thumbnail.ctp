<?php echo $this->element('admin/admin_project_menu') ?>

<?php echo $this->Form->create('Project', array(
        'type' => 'file', 'inputDefaults' => array(
                'div' => false, 'class' => 'form-control'
        )
)); ?>

    <div class="clearfix">

        <div class="form-group">
            <?php echo $this->Form->input('thumbnail_type', array(
                    'type' => 'select', 'options' => array(
                            1 => '画像', 2 => '動画'
                    ), 'label' => 'サムネイルの種類', 'onChange' => 'change_type();', 'id' => 'thumbnail_type'
            )) ?>
        </div>

        <div id="thumbnail_image">
            <div class="form-group">
                <?php if($this->Label->link($project['Project']['pic'])): ?>
                    <?php echo $this->Label->image($project['Project']['pic'], array('class' => 'img-responsive')) ?>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <?php echo $this->Form->input('pic', array(
                        'type' => 'file', 'label' => 'サムネイル画像'
                )); ?>
            </div>
        </div>

        <div id="thumbnail_movie">
            <div class="form-gorup">
                <?php if(!empty($project['Project']['thumbnail_youtube_code'])): ?>
                    <iframe class="youtube"
                            src="//www.youtube.com/embed/<?php echo h($project['Project']['thumbnail_youtube_code']) ?>"
                            frameborder="0" allowfullscreen></iframe>
                <?php endif ?>
            </div>

            <br>
            <div class="form-group">
                <?php echo $this->Form->input('thumbnail_youtube_code', array(
                        'label' => 'Youtube URL(コード)', 'onChange' => 'youtube_code($(this));'
                )); ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo $this->Form->submit('更新', array('class' => 'col-xs-4 btn btn-success')) ?>
        </div>
        <br><br><br>
    </div>


<?php echo $this->Form->end(); ?>
    <br><br>

<?php $this->start('script') ?>
    <script>
        window.onresize = function () {
            resize_youtube();
        };

        $(document).ready(function () {
            change_type();
        });

        function resize_youtube() {
            $('.youtube').css('height', $('.youtube').width() * 430 / 710);
        }

        function change_type() {
            if ($('#thumbnail_type').val() == 1) { //画像
                $('#thumbnail_image').removeClass('none');
                $('#thumbnail_movie').addClass('none');
            } else { //動画
                $('#thumbnail_image').addClass('none');
                $('#thumbnail_movie').removeClass('none');
            }
            resize_youtube();
        }

        function youtube_code(t) {
            var code = t.val();
            var re = /http[s]?:\/\/www\.youtube\.com\/watch\?v=(.+)/;
            var match = re.exec(code);
            if (match) {
                code = match[1];
                t.val(code);
            } else {
                re = /http[s]?:/;
                match = re.exec(code);
                if (match) {
                    alert('正しいYouTubeのURLかコードを入力してください。');
                }
            }
        }
    </script>
<?php $this->end(); ?>