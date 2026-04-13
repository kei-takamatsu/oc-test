<?php
App::uses('AppHelper', 'View/Helper');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

class UserHelper extends AppHelper
{

    public $helpers = array(
            'Html', 'Filebinder.Label'
    );

    /**
     * user_idからプロフィール画像を取得する関数(48px)
     */
    public function get_user_img_md_from_user_id($user_id, $w = 48)
    {
        $save_dir_path = '/upload/User/'.$user_id.'/img';
        $dir           = new Folder(WWW_ROOT.$save_dir_path);
        $files         = $dir->find('.*_48x48.*', true);
        if(!empty($files[0])){
            return $this->Html->image($save_dir_path.'/'.$files[0], array(
                    'class' => 'img-circle', 'width' => $w, 'height' => $w
            ));
        }else{
            return $this->Html->image('user.png', array(
                    'width' => $w, 'height' => $w, 'class' => 'img-circle'
            ));
        }
    }

    /**
     * userからプロフィール画像を取得する関数(100px)
     * img項目がないuserでOK。user_idとfacebook_idしか使わないから
     */
    public function get_user_img_md_from_user_100($user, $w = 100)
    {
        $save_dir_path = '/upload/User/'.$user['User']['id'].'/img';
        $dir           = new Folder(WWW_ROOT.$save_dir_path);
        $files         = $dir->find('.*_48x48.*', true);
        if(!empty($files[0])){
            return $this->Html->image($save_dir_path.'/'.str_replace("_48x48", '', $files[0]), array(
                    'class' => 'img-circle', 'width' => $w, 'height' => $w
            ));
        }elseif(!empty($user['User']['facebook_id'])){
            return $this->Html->image('https://graph.facebook.com/'.$user['User']['facebook_id']
                                      .'/picture?type=normal', array(
                    'width' => $w, 'height' => $w, 'class' => 'img-circle'
            ));
        }else{
            return $this->Html->image('user.png', array(
                    'width' => $w, 'height' => $w, 'class' => 'img-circle'
            ));
        }
    }

    /**
     * プロフィール画像の取得(200*200)
     * @param array $user
     * @param int   $w
     * @return string $img_url
     */
    public function get_user_img($user, $w = 200)
    {
        if($this->Label->link($user['User']['img'])){
            return $this->Label->image($user['User']['img'], array(
                    'class' => 'img-circle', 'width' => $w, 'height' => $w
            ));
        }elseif(!empty($user['User']['facebook_id'])){
            return $this->Html->image('https://graph.facebook.com/'.$user['User']['facebook_id']
                                      .'/picture?type=normal', array(
                    'width' => $w, 'height' => $w, 'class' => 'img-circle'
            ));
        }else{
            return $this->Html->image('user.png', array(
                    'width' => $w, 'height' => $w, 'class' => 'img-circle'
            ));
        }
    }

    /**
     * プロフィール画像の取得（18*18)
     */
    public function get_user_img_sm($user)
    {
        return $this->get_user_img_md($user, 18);
    }

    /**
     * 48*48　プロフィール画像取得
     * 引数$wに48以外を渡すと、そのサイズの正方形で表示いたします
     */
    public function get_user_img_md($user, $w = 48)
    {
        if($this->Label->url_thumb($user['User']['img'], 48, 48)){
            if($w != 48){
                return $this->Label->image_thumb($user['User']['img'], 48, 48, array(
                        'class' => 'img-circle', 'width' => $w
                ));
            }else{
                return $this->Label->image_thumb($user['User']['img'], 48, 48, array('class' => 'img-circle'));
            }
        }elseif(!empty($user['User']['facebook_id'])){
            return $this->Html->image('https://graph.facebook.com/'.$user['User']['facebook_id'].'/picture?type', array(
                    'width' => $w, 'height' => $w, 'class' => 'img-circle'
            ));
        }else{
            return $this->Html->image('user.png', array(
                    'width' => $w, 'height' => $w, 'class' => 'img-circle'
            ));
        }
    }

}