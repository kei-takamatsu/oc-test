function footer_menu() {
    if ($('.menu_sp').is(':visible')) {
        $('.menu_sp').slideUp(100);
        $('.btn_sp').css('background-color', 'rgba(0,0,0,.8)');
    } else {
        $('.btn_sp').css('background-color', '#09c');
        $('.menu_sp').slideDown(100);
    }
}

function resize_movie(max_w) {
    $('.movie:visible').css('width', '100%');
    if ($('.movie:visible').width() > max_w) $('.movie:visible').css('width', max_w);
    $('.movie:visible').css('height', Math.round($('.movie:visible').width() * 430 / 710));
}

function flash(msg) {
    $('.flash_wrap').remove();
    $('body').append('<div class="flash_wrap" onclick="$(this).hide();"><div class="flash">' + msg + '</div></div>');
}

function show_loader(t, left) {
    var l = $('#loader').clone();
    t.after(l);
    l.show();
    l.css('display', 'inline');
    if (left) {
        l.css('text-align', 'left');
    } else {
        l.css('text-align', 'center');
        $('#loader_content').css('margin', '0 auto');
    }

    l.css('width', '100%');

    return;
}

function random_str(num) {
    var base_str = 'abcdefghijklnnopqrstuvwxyz';
    var str = '';
    for (var i = 0; i < num; i++) {
        str += base_str.charAt(Math.round(Math.random() * 26));
    }
    return str;
}

//動画URLチェック
function check_movie(t) {
    var url = t.val();
    if (!url) return null;

    var re = /^http[s]?:\/\/(?:www\.)?youtube\.com\/watch\?v=(.+)$/;
    var match = re.exec(url);
    if (match) {
        return ['youtube', match[1]];
    } else {
        re = /^http[s]?:\/\/(?:www\.)?vimeo\.com\/([0-9]+)$/;
        match = re.exec(url);
        if (!match) {
            alert('URLの形式が正しくありません。');
            return null;
        }
        return ['vimeo', match[1]];
    }
}

function back_top() {
    $("html,body").animate({scrollTop: 0}, 400);
}