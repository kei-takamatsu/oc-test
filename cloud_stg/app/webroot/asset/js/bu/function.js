$(function() {
    $('#key_visual_wrap').vegas({
        slides: [
            { src: './asset/image/key_main.jpg' }
        ],
        overlay: '', //オーバーレイのパターン画像を選択
        transition: 'fade', //スライドを遷移させる際のアニメーション
        transitionDuration: 4000, //スライドの遷移アニメーションの時間
        delay: 10000, //スライド切り替え時の遅延時間
        animation: 'kenburns', //スライドの切り替えアニメーション
        animationDuration: 20000,
    });
});