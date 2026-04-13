<?php
App::uses('AppHelper', 'View/Helper');
App::uses('Sanitize', 'Utility');

class SettingHelper extends AppHelper
{
    public $helpers = array(
            'Project', 'Filebinder.Label'
    );

    public function display_content($setting, $content_no = 1)
    {
        if($setting['content_type'.$content_no] == 'text'){
            echo '<div class="top_box_content text">';
            $this->display_text($setting, $content_no);
            echo '</div>';
        }elseif($setting['content_type'.$content_no] == 'img'){
            $this->display_img($setting, $content_no);
        }else{
            $this->display_movie($setting, $content_no);
        }
    }

    public function display_text($setting, $content_no = 1)
    {
        echo '<h1>'.$setting['txt_content'.$content_no].'</h1>';
    }

    public function display_img($setting, $content_no = 1)
    {
        if($this->Label->url($setting['top_box_img'.$content_no])){
            echo $this->Label->image($setting['top_box_img'.$content_no], array(
                    'class' => 'top_box_img', 'style' => 'max-width:80%; padding:10px 0;'
            ));
        }
    }

    public function display_movie($setting, $content_no = 1)
    {
        if(!empty($setting['movie_code'.$content_no])){
            if($setting['movie_type'.$content_no] == 'youtube'){
                echo '<iframe class="movie top_box_content" src="//www.youtube.com/embed/'.$setting['movie_code'
                                                                                                    .$content_no]
                     .'" frameborder="0" allowfullscreen></iframe>';
            }else{
                echo '<iframe class="movie top_box_content" src="//player.vimeo.com/video/'.$setting['movie_code'
                                                                                                     .$content_no]
                     .'?title=0&amp;byline=0&amp;portrait=0&amp;color=9c1e1e" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
            }
        }
    }

    public function get_token()
    {
    }

}