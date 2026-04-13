var file_datas = new Array();

function client_resize(t, e, x, y, preview_id, field_name) {
    var file = e.target.files[0];

    if (!file.type.match(/^image\/(png|jpeg|gif)$/)) return;
    var img = new Image();
    var reader = new FileReader();

    var maxWidth = x;
    var maxHeight = y;

    reader.onload = function (e) {
        var data = e.target.result;

        img.onload = function () {

            var iw = img.naturalWidth, ih = img.naturalHeight;
            var width = iw, height = ih;

            var orientation;

            // JPEGの場合には、EXIFからOrientation（回転）情報を取得
            if (data.split(',')[0].match('jpeg')) {
                orientation = getOrientation(data);
            }
            // JPEG以外や、JPEGでもEXIFが無い場合などには、標準の値に設定
            orientation = orientation || 1;

            // ９０度回転など、縦横が入れ替わる場合には事前に最大幅、高さを入れ替えておく
            if (orientation > 4) {
                var tmpMaxWidth = maxWidth;
                maxWidth = maxHeight;
                maxHeight = tmpMaxWidth;
            }

            if (maxWidth && maxHeight) {
                if (width > maxWidth || height > maxHeight) {
                    var ratio = width / maxWidth;
                    if (ratio <= height / maxHeight) {
                        ratio = height / maxHeight;
                    }
                    width = Math.floor(img.width / ratio);
                    height = Math.floor(img.height / ratio);
                }
            } else if (maxWidth) {
                if (width > maxWidth) {
                    var ratio = width / maxWidth;
                    height = Math.floor(img.height / ratio);
                    width = Math.floor(img.width / ratio);
                }
            } else {
                if (height > maxHeight) {
                    var ratio = height / maxHeight;
                    width = Math.floor(img.width / ratio);
                    height = Math.floor(img.height / ratio);
                }
            }

            var canvas = $('<canvas>');
            var ctx = canvas[0].getContext('2d');
            ctx.save();

            // EXIFのOrientation情報からCanvasを回転させておく
            transformCoordinate(canvas, width, height, orientation);

            // iPhoneのサブサンプリング問題の回避
            var subsampled = detectSubsampling(img);
            if (subsampled) {
                iw /= 2;
                ih /= 2;
            }
            var d = 1024; // size of tiling canvas
            var tmpCanvas = $('<canvas>');
            tmpCanvas[0].width = tmpCanvas[0].height = d;
            var tmpCtx = tmpCanvas[0].getContext('2d');
            var vertSquashRatio = detectVerticalSquash(img, iw, ih);
            var dw = Math.ceil(d * width / iw);
            var dh = Math.ceil(d * height / ih / vertSquashRatio);
            var sy = 0;
            var dy = 0;
            while (sy < ih) {
                var sx = 0;
                var dx = 0;
                while (sx < iw) {
                    tmpCtx.clearRect(0, 0, d, d);
                    tmpCtx.drawImage(img, -sx, -sy);
                    ctx.drawImage(tmpCanvas[0], 0, 0, d, d, dx, dy, dw, dh);
                    sx += d;
                    dx += dw;
                }
                sy += d;
                dy += dh;
            }
            ctx.restore();
            tmpCanvas = tmpCtx = null;

            // プレビューするために<img>用のDataURLを作成
            // (スマホなどの狭小画面でも画像の全体図が見れるように、解像度を保ったたま縮小表示したいので)
            var displaySrc = ctx.canvas.toDataURL(file.type, .9);
            var displayImg = $('<img>').attr({'src': displaySrc, 'id': 'preview'}).css({
                'maxWidth': '90%',
                'maxHeight': '90%'
            });
            $('#' + preview_id).html(displayImg);

            // FormDataに縮小後の画像データを追加する
            // Blob形式にすることで、サーバ側は従来のままのコードで対応可能
            var blob = dataURLtoBlob(displaySrc);

            if (field_name in file_datas) {
                file_datas[field_name]['file'] = file;
                file_datas[field_name]['blob'] = blob;
            } else {
                file_datas[field_name] = new Array();
                file_datas[field_name]['file'] = file;
                file_datas[field_name]['blob'] = blob;
            }
        }
        img.src = data;
    }
    reader.readAsDataURL(file);

    // JPEGのEXIFからOrientationのみを取得する
    function getOrientation(imgDataURL) {
        var byteString = atob(imgDataURL.split(',')[1]);
        var orientaion = byteStringToOrientation(byteString);
        return orientaion;

        function byteStringToOrientation(img) {
            var head = 0;
            var orientation;
            while (1) {
                if (img.charCodeAt(head) == 255 & img.charCodeAt(head + 1) == 218) {
                    break;
                }
                if (img.charCodeAt(head) == 255 & img.charCodeAt(head + 1) == 216) {
                    head += 2;
                }
                else {
                    var length = img.charCodeAt(head + 2) * 256 + img.charCodeAt(head + 3);
                    var endPoint = head + length + 2;
                    if (img.charCodeAt(head) == 255 & img.charCodeAt(head + 1) == 225) {
                        var segment = img.slice(head, endPoint);
                        var bigEndian = segment.charCodeAt(10) == 77;
                        if (bigEndian) {
                            var count = segment.charCodeAt(18) * 256 + segment.charCodeAt(19);
                        } else {
                            var count = segment.charCodeAt(18) + segment.charCodeAt(19) * 256;
                        }
                        for (i = 0; i < count; i++) {
                            var field = segment.slice(20 + 12 * i, 32 + 12 * i);
                            if ((bigEndian && field.charCodeAt(1) == 18) || (!bigEndian && field.charCodeAt(0) == 18)) {
                                orientation = bigEndian ? field.charCodeAt(9) : field.charCodeAt(8);
                            }
                        }
                        break;
                    }
                    head = endPoint;
                }
                if (head > img.length) {
                    break;
                }
            }
            return orientation;
        }
    }

    // iPhoneのサブサンプリングを検出
    function detectSubsampling(img) {
        var iw = img.naturalWidth, ih = img.naturalHeight;
        if (iw * ih > 1024 * 1024) {
            var canvas = $('<canvas>');
            canvas[0].width = canvas[0].height = 1;
            var ctx = canvas[0].getContext('2d');
            ctx.drawImage(img, -iw + 1, 0);
            return ctx.getImageData(0, 0, 1, 1).data[3] === 0;
        } else {
            return false;
        }
    }

    // iPhoneの縦画像でひしゃげて表示される問題
    function detectVerticalSquash(img, iw, ih) {
        var canvas = $('<canvas>');
        canvas[0].width = 1;
        canvas[0].height = ih;
        var ctx = canvas[0].getContext('2d');
        ctx.drawImage(img, 0, 0);
        var data = ctx.getImageData(0, 0, 1, ih).data;
        var sy = 0;
        var ey = ih;
        var py = ih;
        while (py > sy) {
            var alpha = data[(py - 1) * 4 + 3];
            if (alpha === 0) {
                ey = py;
            } else {
                sy = py;
            }
            py = (ey + sy) >> 1;
        }
        var ratio = (py / ih);
        return (ratio === 0) ? 1 : ratio;
    }

    function transformCoordinate(canvas, width, height, orientation) {
        if (orientation > 4) {
            canvas[0].width = height;
            canvas[0].height = width;
        } else {
            canvas[0].width = width;
            canvas[0].height = height;
        }
        var ctx = canvas[0].getContext('2d');
        switch (orientation) {
            case 2:
                // horizontal flip
                ctx.translate(width, 0);
                ctx.scale(-1, 1);
                break;
            case 3:
                // 180 rotate left
                ctx.translate(width, height);
                ctx.rotate(Math.PI);
                break;
            case 4:
                // vertical flip
                ctx.translate(0, height);
                ctx.scale(1, -1);
                break;
            case 5:
                // vertical flip + 90 rotate right
                ctx.rotate(0.5 * Math.PI);
                ctx.scale(1, -1);
                break;
            case 6:
                // 90 rotate right
                ctx.rotate(0.5 * Math.PI);
                ctx.translate(0, -height);
                break;
            case 7:
                // horizontal flip + 90 rotate right
                ctx.rotate(0.5 * Math.PI);
                ctx.translate(width, -height);
                ctx.scale(-1, 1);
                break;
            case 8:
                // 90 rotate left
                ctx.rotate(-0.5 * Math.PI);
                ctx.translate(-width, 0);
                break;
            default:
                break;
        }
    }

    function dataURLtoArrayBuffer(data) {
        var byteString = atob(data.split(',')[1]);
        var ab = new ArrayBuffer(byteString.length);
        var ia = new Uint8Array(ab);
        for (var i = 0; i < byteString.length; i++) {
            ia[i] = byteString.charCodeAt(i);
        }
        return ab
    }

    function dataURLtoBlob(data) {
        var mimeString = data.split(',')[0].split(':')[1].split(';')[0];
        var ab = dataURLtoArrayBuffer(data);
        var bb = (window.BlobBuilder || window.WebKitBlobBuilder || window.MozBlobBuilder);
        if (bb) {
            bb = new (window.BlobBuilder || window.WebKitBlobBuilder || window.MozBlobBuilder)();
            bb.append(ab);
            return bb.getBlob(mimeString);
        } else {
            bb = new Blob([ab], {
                'type': (mimeString)
            });
            return bb;
        }
    }

}

//登録後リダイレクトしない(レスポンスデータはHTML形式。引数のredirect_urlは利用しない)
function save_form_data(t, e, url, redirect_url, not_empty) {
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

    $.ajax({
        url: url,
        type: 'POST',
        data: form_data,
        dataType: 'json',
        contentType: false,
        processData: false,
        async: true,
        beforeSend: function () {
            show_loader(t);
            t.hide();
        },
        success: function (data) {
            flash(data.msg);
        },
        error: function () {
            flash('登録に失敗しました。恐れ入りますが再度お試しください。');
        },
        complete: function () {
            t.next().remove();
            t.show();
            if (!not_empty) {
                file_datas = new Array();
            }
        }
    });
    return false;
}

//登録後リダイレクトする(レスポンスデータはJSON形式）
function save_form_data_redirect(t, e, url, redirect_url, not_empty) {
    $('div[id^=error-]').hide();

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

    $.ajax({
        url: url,
        type: 'POST',
        data: form_data,
        dataType: 'json',
        contentType: false,
        processData: false,
        async: true,
        beforeSend: function () {
            show_loader(t);
            t.hide();
        },
        success: function (data) {
            if (data.status) {
                location.href = redirect_url;
            } else {
                flash(data.msg);
                if (data.errors) {
                    for (key in data.errors) {
                        $('#error-' + key).html(data.errors[key]).show();
                    }
                }
            }
        },
        error: function () {
            flash('登録に失敗しました。恐れ入りますが再度お試しください。');
        },
        complete: function () {
            t.next().remove();
            t.show();
            if (!not_empty) {
                file_datas = new Array();
            }
        }
    });
}

function change_file_name(random_str, file) {
    switch (file.type) {
        case 'image/png':
            return random_str + '.png';
        case 'image/jpeg':
            return random_str + '.jpg';
        case 'image/gif':
            return random_str + '.gif';
    }
}