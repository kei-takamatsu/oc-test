<?php
App::uses('AppModel', 'Model');

class Contact extends AppModel
{
    public $useTable = false;
    public $validate = array(
            'name' => array(
                    'notblank' => array(
                            'rule' => array('notblank'), 'message' => '名前を入力してください。', 'allowEmpty' => false,
                            'required' => true
                    ),
            ), 'mail' => array(
                    'notblank' => array(
                            'rule' => array('notblank'), 'message' => 'メールアドレスを入力してください。', 'allowEmpty' => false,
                            'required' => true
                    ), 'email' => array(
                            'rule' => array('email'), 'message' => '正しいメールアドレスを入力してください。'
                    )
            ), 'content' => array(
                    'notblank' => array(
                            'rule' => array('notblank'), 'message' => 'お問い合わせ内容を入力してください。', 'allowEmpty' => false,
                            'required' => true,
                    ),
            ),
    );
}