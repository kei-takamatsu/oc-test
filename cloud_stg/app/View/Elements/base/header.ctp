<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="ja" xml:lang="ja" dir="ltr"
>
<head>
    <meta charset="utf-8">
    <title>
        <?php
        if(!empty($top_title)){
            echo h($top_title);
        }else{
            echo (isset($title) ? $title.' - ' : '').h($setting['site_name']);
        }
        ?>
    </title>
    <meta name="Description" content="<?php echo isset($description) ? $description : '' ?>"/>
    <meta name="Keywords" content="<?php echo h($setting['site_keywords']) ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo $this->fetch('ogp'); ?>
    <?php echo $this->Html->meta('icon'); ?>

    <?php echo $this->Html->css('/bootstrap/css/bootstrap.min')."\n" ?>
    <?php echo $this->Html->css('/bootstrap/css/elusive-webfont') ?>

    <?php echo $this->Html->css('/asset/css/style') ?>

    <?php echo $this->Html->css('common'); ?>
        <link rel="stylesheet" media="screen and (max-width:480px)" type="text/css" href="/asset/css/smart.css" />
        <link rel="stylesheet" media="screen and (min-width:481px) and (max-width: 1024px)" type="text/css" href="/asset/css/tablet.css" /> 
        <link rel="stylesheet" media="screen and (min-width:1025px) and (max-width: 1400px)" type="text/css" href="/asset/css/style2.css" />  
        <link rel="stylesheet" media="screen and (min-width:1401px)" type="text/css" href="/asset/css/style.css" />
        <script src="/asset/js/jquery-3.3.1.min.js"></script>
        <script src="/asset/js/slick.min.js"></script>
        <script src="/asset/js/anime.js"></script>

        <script>
            (function(d) {
                var config = {
                    kitId: 'qju4kjf',
                    scriptTimeout: 3000,
                    async: true
                },
                    h=d.documentElement,t=setTimeout(function(){h.className=h.className.replace(/\bwf-loading\b/g,"")+" wf-inactive";},config.scriptTimeout),tk=d.createElement("script"),f=false,s=d.getElementsByTagName("script")[0],a;h.className+=" wf-loading";tk.src='https://use.typekit.net/'+config.kitId+'.js';tk.async=true;tk.onload=tk.onreadystatechange=function(){a=this.readyState;if(f||a&&a!="complete"&&a!="loaded")return;f=true;clearTimeout(t);try{Typekit.load(config)}catch(e){}};s.parentNode.insertBefore(tk,s)
            })(document);
        </script>
        <script>
              (function(d) {
                var config = {
                  kitId: 'gxe5kuh',
                  scriptTimeout: 3000,
                  async: true
                },
                h=d.documentElement,t=setTimeout(function(){h.className=h.className.replace(/\bwf-loading\b/g,"")+" wf-inactive";},config.scriptTimeout),tk=d.createElement("script"),f=false,s=d.getElementsByTagName("script")[0],a;h.className+=" wf-loading";tk.src='https://use.typekit.net/'+config.kitId+'.js';tk.async=true;tk.onload=tk.onreadystatechange=function(){a=this.readyState;if(f||a&&a!="complete"&&a!="loaded")return;f=true;clearTimeout(t);try{Typekit.load(config)}catch(e){}};s.parentNode.insertBefore(tk,s)
              })(document);
        </script>
        <script>
            $(function() {
                var topBtn = $('#page-top');    
                topBtn.hide();
                $(window).scroll(function () {
                    if ($(this).scrollTop() > 100) {
                        topBtn.fadeIn();
                    } else {
                        topBtn.fadeOut();
                    }
                });
                topBtn.click(function () {
                    $('body,html').animate({
                        scrollTop: 0
                    }, 500);
                    return false;
                });
            });
            $(function(){
                $('a[href^="#"]').click(function() {
                    var speed = 400;
                    var href= $(this).attr("href");
                    var target = $(href == "#" || href == "" ? 'html' : href);
                    var position = target.offset().top;
                    $('body,html').animate({scrollTop:position}, speed, 'swing');
                    return false;
                });
            });
            $(function () {
                $('.menu2').on('click', function () {
                    $(this).toggleClass('active');
                    $("#nav2").toggleClass('active');
                })
            }) 
            $(function () {
                $('#nav2 a').on('click', function () {
                    $('#nav2').toggleClass('active');
                    $(".menu2").toggleClass('active');
                })
            });
            document.addEventListener('DOMContentLoaded', function(){
                var $tab__link = $(".tab2__link");
                var $tab_body_item = $(".tab2-body__item");
                $tab__link.on("click",function(e){
                    var target = $(e.currentTarget);
                    //タブの表示非表示
                    $tab__link.removeClass("on");
                    target.addClass("on");
                    //タブの中身の表示非表示
                    var num =　target.data("tab-body");
                    $tab_body_item.removeClass("on");
                    $(".tab2-body__item--"+num).addClass("on");
                });
            });
        </script>
    <?php echo $this->fetch('css') ?>
</head>

<?php if(!empty($login_page)): ?>
<body class="login">
<?php else: ?>
<body>
<?php endif ?>
<?php echo $this->element('admin/google/analytics') ?>
<div id="fb-root"></div>
<script>(function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/ja_JP/all.js#xfbml=1";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
<div id="wrapper">