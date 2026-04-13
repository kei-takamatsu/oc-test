//上段のメインコンテンツメニュー
function new_text_top(t) {
    cancel_edit();
    t.parent().nextAll('.new_text').slideToggle(100);
    $('.new_item').not(t.parent().nextAll('.new_text')).hide();
    $('.add_here_menu').hide();
}

function new_img_top(t) {
    cancel_edit();
    t.parent().nextAll('.new_img').slideToggle(100);
    $('.new_item').not(t.parent().nextAll('.new_img')).hide();
    $('.add_here_menu').hide();
}

function new_movie_top(t) {
    cancel_edit();
    t.parent().nextAll('.new_movie').slideToggle(100);
    $('.new_item').not(t.parent().nextAll('.new_movie')).hide();
    $('.add_here_menu').hide();
}

//コンテンツ間のサブコンテンツメニュー
function new_text(t) {
    t.parents('.add_here_menu').nextAll('.new_text').slideToggle(100);
    $('.new_item').not(t.parents('.add_here_menu').nextAll('.new_text')).hide();
}

function new_img(t) {
    t.parents('.add_here_menu').nextAll('.new_img').slideToggle(100);
    $('.new_item').not(t.parents('.add_here_menu').nextAll('.new_img')).hide();
}

function new_movie(t) {
    t.parents('.add_here_menu').nextAll('.new_movie').slideToggle(100);
    $('.new_item').not(t.parents('.add_here_menu').nextAll('.new_movie')).hide();
}

function display_add_here(t) {
    if (t.children('.add_here_menu').is(':hidden')) {
        t.children('.add_here').show();
    }
}

function remove_add_here() {
    $('.add_here').hide();
}

function display_add_here_menu(t) {
    cancel_edit();
    $('.add_here_menu').hide();
    $('.add_here').hide();
    t.parent('.add_here').next('.add_here_menu').show();
    $('.new_item').hide();
}

function close_add_here_menu(t) {
    $('.add_here_menu').hide();
    $('.new_item').hide();
}

//テキスト追加
function save_text(t, save_mode, url) {
    if (!t.parent().prev().children('div').children('.text_content').val()) return;

    t.parent().nextAll('.open_mode').val(save_mode);

    var order_no = t.closest('.sort_item').attr('name');
    if (!order_no) order_no = 0;

    $.ajax({
        type: 'post',
        url: url + '/' + order_no,
        data: t.closest('.new_item').children('form.text_form').serialize(),
        dataType: 'html',
        async: true,
        beforeSend: function () {
            show_loader(t, 1);
            t.hide();
        },
        success: function (data) {
            t.parent().prev().children('div').children('.text_content').val('');
            if (order_no) {
                t.closest('.sort_item').before(data);
            } else {
                $('#project_contents > #sortable').append(data);
            }
            resize_movie();
        },
        error: function () {
            flash('登録に失敗しました。恐れ入りますが再度お試しください。');
        },
        complete: function () {
            t.next().remove();
            t.parent().prev().children('div').children('.txt_content').val('');
            t.show();
            close_add_here_menu();
        }
    });
}

//タグで囲む
function wrap(t, tag_name) {
    wrap_tag(t.parent().next().children('div').children('.text_content'), tag_name);
}

//テキスト更新
function edit_text(t, url) {
    $.ajax({
        type: 'post',
        url: url + '/' + t.closest('.sort_item').attr('name'),
        data: t.parent().prev().children('div').children('.text_content').serialize(),
        dataType: 'html',
        async: true,
        beforeSend: function () {
            show_loader(t, 1);
            t.hide();
        },
        success: function (data) {
            t.closest('.project_content').html(data);
            resize_movie();
        },
        error: function () {
            t.next().remove();
            t.parent().prev().children('div').children('.text_content').val('');
            t.show();
            flash('登録に失敗しました。恐れ入りますが再度お試しください。');
        },
        complete: function () {
            $('.project_content').css('background', 'transparent');
        }
    });
}

//画像追加
function save_img(t, e, save_mode, url) {
    t.parent().nextAll('.open_mode').val(save_mode);

    var order_no = t.closest('.sort_item').attr('name');
    if (!order_no) order_no = 0;

    t.closest('.new_item').children('form.img_form').attr('id', 'img_form_now');
    var form_data = new FormData();

    e.preventDefault();

    // フォームの全ての入力値をFormDataに追加
    var formAry = t.closest('form').serializeArray()
    $.each(formAry, function (i, field) {
        form_data.append(field['name'], field['value']);
    });

    if (file_datas) {
        for (key in file_datas) {
            form_data.append(key, file_datas[key]['blob'], change_file_name(random_str(15), file_datas[key]['file']));
        }
    }

    t.closest('.new_item').children('form.img_form').attr('id', '');

    $.ajax({
        type: 'post',
        url: url + '/' + order_no,
        data: form_data,
        dataType: 'html',
        async: true,
        processData: false,
        contentType: false,
        beforeSend: function () {
            show_loader(t, 1);
            t.hide();
        },
        success: function (data) {
            if (order_no) {
                t.closest('.sort_item').before(data);
            } else {
                $('#project_contents > #sortable').append(data);
            }
            resize_movie();
        },
        error: function () {
            flash('登録に失敗しました。恐れ入りますが再度お試しください。');
        },
        complete: function () {
            t.next().remove();
            $('.img_input').val('');
            $('.img_caption').val('');
            $('.img_list').empty();
            t.show();
            close_add_here_menu();
            file_datas = new Array();
        }
    });
}

//画像編集
function edit_img(t, e, url) {
    t.closest('.content_edit_form').children('form.img_form').attr('id', 'img_form_now');

    var form_data = new FormData();

    e.preventDefault();

    // フォームの全ての入力値をFormDataに追加
    var formAry = t.closest('form').serializeArray()
    $.each(formAry, function (i, field) {
        form_data.append(field['name'], field['value']);
    });

    if (file_datas) {
        for (key in file_datas) {
            form_data.append(key, file_datas[key]['blob'], change_file_name(random_str(15), file_datas[key]['file']));
        }
    }

    t.closest('.content_edit_form').children('form.img_form').attr('id', '');

    $.ajax({
        type: 'post',
        url: url + '/' + t.closest('.sort_item').attr('name'),
        data: form_data,
        dataType: 'html',
        async: true,
        processData: false,
        contentType: false,
        beforeSend: function () {
            show_loader(t, 1);
            t.hide();
        },
        success: function (data) {
            console.log(data);
            t.closest('.project_content').html(data);
            resize_movie();
        },
        error: function () {
            t.next().remove();
            t.parent().prev().children('div').children('.movie_url').val('');
            t.show();
            flash('登録に失敗しました。恐れ入りますが再度お試しください。');
        },
        complete: function () {
            $('.project_content').css('background', 'transparent');
            file_datas = new Array();
        }
    });
}

//動画追加
function save_movie(t, save_mode, url) {
    var type_code = check_movie(t.parent().prev().children('div').children('.movie_url'));
    if (!type_code[1]) return;

    t.parent().prev().children('div').children('.movie_url').val(type_code[1]);
    t.parent().nextAll('.movie_type').val(type_code[0]);
    t.parent().nextAll('.open_mode').val(save_mode);

    var order_no = t.closest('.sort_item').attr('name');
    if (!order_no) order_no = 0;

    $.ajax({
        type: 'post',
        url: url + '/' + order_no,
        data: t.closest('.new_item').children('form.movie_form').serialize(),
        dataType: 'html',
        async: true,
        beforeSend: function () {
            show_loader(t, 1);
            t.hide();
        },
        success: function (data) {
            if (order_no) {
                t.closest('.sort_item').before(data);
            } else {
                $('#project_contents > #sortable').append(data);
            }
            resize_movie();
        },
        error: function () {
            flash('登録に失敗しました。恐れ入りますが再度お試しください。');
        },
        complete: function () {
            t.next().remove();
            t.parent().prev().children('div').children('.movie_url').val('');
            t.show();
            close_add_here_menu();
        }
    });
}

//動画編集
function edit_movie(t, url) {
    var type_code = check_movie(t.parent().prev().children('div').children('.movie_url'));
    if (!type_code) return;

    t.parent().nextAll('.movie_type').val(type_code[0]);
    t.parent().prev().children('div').children('.movie_url').val(type_code[1]);

    $.ajax({
        type: 'post',
        url: url + '/' + t.closest('.sort_item').attr('name'),
        data: t.closest('.content_edit_form').children('form.movie_form').serialize(),
        dataType: 'html',
        async: true,
        beforeSend: function () {
            show_loader(t, 1);
            t.hide();
        },
        success: function (data) {
            t.closest('.project_content').html(data);
            resize_movie();
        },
        error: function () {
            t.next().remove();
            t.parent().prev().children('div').children('.movie_url').val('');
            t.show();
            flash('登録に失敗しました。恐れ入りますが再度お試しください。');
        },
        complete: function () {
            $('.project_content').css('background', 'transparent');
        }
    });
}

//コンテンツ編集
function content_edit(t) {
    cancel_edit();
    remove_add_here();
    $('.new_item').hide();
    $('.add_here_menu').hide();

    t.parent('.project_content_menu').nextAll('.content_view').hide();
    t.parent('.project_content_menu').nextAll('.content_edit_form').show();
    t.closest('.project_content').css('background', '#c5e5f9');
}

//コンテンツ編集のキャンセル
function cancel_edit() {
    $('.content_edit_form').prev('.content_view').show();
    $('.content_edit_form').hide();
    $('.project_content').css('background', 'transparent');
}

//コンテンツ削除
function trash(t, url) {
    if (window.confirm('削除しますか？')) {
        $.ajax({
            type: 'post',
            url: url,
            data: {id: t.closest('.sort_item').attr('name')},
            dataType: 'html',
            async: true,
            beforeSend: function () {
                show_loader(t);
                t.hide();
                t.parent().children().attr('disabled', 'disabled');
            },
            success: function (data) {
                t.closest('.sort_item').remove();
            },
            error: function () {
                t.show();
                t.next().remove();
                flash('削除に失敗しました。恐れ入りますが再度お試しください。');
                t.parent().children().removeAttr('disabled');
            }
        });
    }
}

//並び替え
function save_sort(t, sort_data, url) {
    $.ajax({
        type: 'post',
        url: url,
        data: sort_data,
        dataType: 'html',
        async: true,
        error: function () {
            flash('登録に失敗しました。恐れ入りますが再度お試しください。');
        }
    });
}

//上に移動
function move_up(t, url) {
    var ue_id = t.closest('.sort_item').prev('.sort_item').attr('name');
    if (!ue_id) return;
    var my_id = t.closest('.sort_item').attr('name');

    $.ajax({
        type: 'post',
        url: url,
        data: {ue_id: ue_id, my_id: my_id},
        dataType: 'json',
        async: true,
        beforeSend: function () {
            show_loader(t, true);
            t.hide();
            t.parent().children().attr('disabled', 'disabled');
        },
        success: function (data) {
            t.closest('.sort_item').after($('.sort_item[name=' + ue_id + ']'));
            t.next().remove();
            t.parent().children().removeAttr('disabled');
            t.show();
        },
        error: function () {
            t.next().remove();
            t.parent().children().removeAttr('disabled');
            t.show();
            flash('登録に失敗しました。恐れ入りますが再度お試しください。');
        }
    });
}

//下に移動
function move_down(t, url) {
    var down_id = t.closest('.sort_item').next('.sort_item').attr('name');
    if (!down_id) return;
    var my_id = t.closest('.sort_item').attr('name');

    $.ajax({
        type: 'post',
        url: url,
        data: {down_id: down_id, my_id: my_id},
        dataType: 'json',
        async: true,
        beforeSend: function () {
            show_loader(t);
            t.hide();
            t.parent().children().attr('disabled', 'disabled');
        },
        success: function (data) {
            t.closest('.sort_item').before($('.sort_item[name=' + down_id + ']'));
            t.next().remove();
            t.parent().children().removeAttr('disabled');
            t.show();
        },
        error: function () {
            t.next().remove();
            t.parent().children().removeAttr('disabled');
            t.show();
            flash('登録に失敗しました。恐れ入りますが再度お試しください。');
        }
    });
}

//公開
function content_open(t, open_url, close_url) {
    $.ajax({
        type: 'post',
        url: open_url,
        data: {id: t.closest('.sort_item').attr('name')},
        dataType: 'json',
        async: true,
        beforeSend: function () {
            show_loader(t);
            t.hide();
            t.parent().children().attr('disabled', 'disabled');
        },
        success: function (data) {
            t.attr('onclick', "content_close($(this),'" + close_url + "', '" + open_url + "');").html('非公開にする');
            t.next().remove();
            t.parent().children().removeAttr('disabled');
            t.show();
        },
        error: function () {
            t.next().remove();
            t.parent().children().removeAttr('disabled');
            t.show();
            flash('登録に失敗しました。恐れ入りますが再度お試しください。');
        }
    });
}

//非公開
function content_close(t, close_url, open_url) {
    $.ajax({
        type: 'post',
        url: close_url,
        data: {id: t.closest('.sort_item').attr('name')},
        dataType: 'json',
        async: true,
        beforeSend: function () {
            show_loader(t);
            t.hide();
            t.parent().children().attr('disabled', 'disabled');
        },
        success: function (data) {
            t.attr('onclick', "content_open($(this),'" + open_url + "', '" + close_url + "');").html('公開する');
            t.next().remove();
            t.parent().children().removeAttr('disabled');
            t.show();
        },
        error: function () {
            t.next().remove();
            t.parent().children().removeAttr('disabled');
            t.show();
            flash('登録に失敗しました。恐れ入りますが再度お試しください。');
        }
    });
}

//tagで囲む
function wrap_tag(area, tag_name) {
    var str_start = str_end = '';

    switch (tag_name) {
        case 'link':
            var url = prompt("URLを入力してください。", "");
            if (!url) return;
            str_start = '<a href="' + url + '" target="_blank">';
            str_end = '</a>';
            break;
        case 'left':
            str_start = '<p style="text-align:left;">';
            str_end = '</p>';
            break;
        case 'center':
            str_start = '<p style="text-align:center;">';
            str_end = '</p>';
            break;
        case 'right':
            str_start = '<p style="text-align:right;">';
            str_end = '</p>';
            break;
        case 'bold':
            str_start = '<span style="font-weight:bold;">';
            str_end = '</span>';
            break;
        case 'h1':
            str_start = '<span style="font-size:45px;">';
            str_end = '</span>';
            break;
        case 'h2':
            str_start = '<span style="font-size:28px;">';
            str_end = '</span>';
            break;
        case 'h3':
            str_start = '<span style="font-size:20px;">';
            str_end = '</span>';
            break;
        default:
            str_start = '<' + tag_name + '>';
            str_end = '</' + tag_name + '>';
    }

    var start = area[0].selectionStart;
    var end = area[0].selectionEnd;
    var val = area.val();
    area.val(
        val.slice(0, start) +
        str_start + val.slice(start, end) + str_end +
        val.slice(end)
    );
}