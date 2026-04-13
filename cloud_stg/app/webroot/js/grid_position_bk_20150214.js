function grid_position() {
    var border_w = 10;
    var w = $(window).width();

    if (w > 700) {
        var grid_lp = 10;
        var grid_iw = 400;
        var grid_w = grid_iw + grid_lp;

        //console.log('w: ' + w);
        //console.log('grid_w: ' + grid_w);

        if (w < grid_w * $('.grid_wrap').length + border_w) {
            var grid_num = Math.floor((w - border_w) / grid_w);
            //console.log('gird_num: ' + grid_num);

            var space = (w - border_w) % grid_w;
            //console.log('space: ' + space);

            if (grid_num < $('.grid_wrap').length) {
                grid_num++;
                //console.log('new_grid_num: ' + grid_num);

                space = (grid_w - space) / grid_num;
                //console.log('1_space: ' + space);

                grid_w -= space;
                //console.log('new_grid_w: ' + grid_w);

                $('.grid_wrap').width(grid_w - grid_lp);
            }
            $('#grid_container').width(grid_w * grid_num + border_w);

        } else {
            $('#grid_container').width(grid_w * $('.grid_wrap').length + border_w);
        }

    } else {
        $('#grid_container').width('100%');
    }

}

function grid_position_report() {
    var border_w = 10;
    var w = $(window).width();

    if (w > 700) {
        var grid_lp = 10;
        var grid_iw = 400;
        var grid_w = grid_iw + grid_lp;

        if (w < grid_w * $('.grid_wrap_report').length + border_w) {
            var grid_num = Math.floor((w - border_w) / grid_w);
            var space = (w - border_w) % grid_w;

            if (grid_num < $('.grid_wrap_report').length) {
                grid_num++;
                space = (grid_w - space) / grid_num;
                grid_w -= space;
                $('.grid_wrap_report').width(grid_w - grid_lp);
            }
            $('#grid_container_report').width(grid_w * grid_num + border_w);

        } else {
            $('#grid_container_report').width(grid_w * $('.grid_wrap_report').length + border_w);
        }

    } else {
        $('#grid_container_report').width('100%');
    }

}

function top_report_position() {
    var border_w = 10;
    var w = $(window).width();

    $('.grid_wrap_report').width($('.grid_wrap').width());
    var grid_w = $('.grid_wrap_report').width() + border_w;

    if (w > 700) {
        if (w > grid_w * $('.grid_wrap_report').length + border_w) {
            $('#grid_container_report').width(grid_w * $('.grid_wrap_report').length + border_w);
        }
    }


}