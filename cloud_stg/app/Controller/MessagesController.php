<?php
App::uses('AppController', 'Controller');

class MessagesController extends AppController
{
    public $uses = array(
            'Message', 'MessagePair'
    );
    public $components = array('Mail');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('hoge');
        $this->layout = 'mypage';
    }

    /**
     * マイページ（メッセージ一覧画面）
     */
    public function index()
    {
        //自分がIDに含まれるメッセージを抽出する。
        //しかし、同じpairのメッセージは最新しか抽出しない。
        //最新のメッセージが自分宛で、未読フラグがある場合は、未読とする
        $user_id        = $this->Auth->user('id');
        $options        = array(
                'conditions' => array(
                        'OR' => array(
                                'MessagePair.last_receiver_id' => $user_id, 'MessagePair.last_sender_id' => $user_id
                        )
                ), 'order' => array('MessagePair.modified' => 'DESC'), 'joins' => array(
                        array(
                                'table' => 'users', 'alias' => 'Receiver', 'type' => 'inner',
                                'conditions' => array('Receiver.id = MessagePair.last_receiver_id'),
                        ), array(
                                'table' => 'users', 'alias' => 'Sender', 'type' => 'inner',
                                'conditions' => array('Sender.id = MessagePair.last_sender_id'),
                        ),
                ), 'fields' => array(
                        'MessagePair.message_pair_id', 'MessagePair.read_flag', 'MessagePair.modified',
                        'MessagePair.last_sender_id', 'Receiver.id', 'Receiver.nick_name', 'Sender.id',
                        'Sender.nick_name'
                )
        );
        $this->paginate = $options;
        $messages       = $this->paginate('MessagePair');
        $this->set(compact('messages'));
    }

    /**
     * 未返信のメールのみの一覧
     */
    public function no_reply()
    {
        $user_id        = $this->Auth->user('id');
        $options        = array(
                'conditions' => array('MessagePair.last_receiver_id' => $user_id,),
                'order' => array('MessagePair.modified' => 'DESC'), 'joins' => array(
                        array(
                                'table' => 'users', 'alias' => 'Receiver', 'type' => 'inner',
                                'conditions' => array('Receiver.id = MessagePair.last_receiver_id'),
                        ), array(
                                'table' => 'users', 'alias' => 'Sender', 'type' => 'inner',
                                'conditions' => array('Sender.id = MessagePair.last_sender_id'),
                        ),
                ), 'fields' => array(
                        'MessagePair.message_pair_id', 'MessagePair.read_flag', 'MessagePair.modified',
                        'MessagePair.last_sender_id', 'Receiver.id', 'Receiver.nick_name', 'Sender.id',
                        'Sender.nick_name'
                )
        );
        $this->paginate = $options;
        $messages       = $this->paginate('MessagePair');
        $this->set(compact('messages'));
    }

    /**
     * メッセージ詳細画面
     * ここからしかメッセージは送れないので送信画面でもある
     */
    public function view($user_id = null)
    {
        if(!$user_id || $user_id == $this->Auth->user('id')){
            $this->redirect('/');
        }
        $to_user = $this->User->findById($user_id);
        if(!$to_user){
            $this->redirect('/');
        }
        $this->set(compact('to_user'));
        $pair_id = $this->MessagePair->make_pair($user_id, $this->Auth->user('id'));
        $pair    = $this->MessagePair->findByMessagePairId($pair_id);
        if($this->request->is('post') || $this->request->is('put')){
            //メッセージ入力チェック
            if(empty($this->request->data['Message']['content']) || preg_match("/^[\s　]*$/u", $this->request->data['Message']['content'])){
                $this->Session->setFlash('メッセージを入力してください');
                return $this->redirect('/messages/view/'.$user_id);
            }
            $this->MessagePair->begin();
            if(!$pair){
                $this->MessagePair->create();
                $pair = array(
                        'MessagePair' => array(
                                'message_pair_id' => $pair_id, 'last_sender_id' => $this->Auth->user('id'),
                                'last_receiver_id' => $to_user['User']['id'], 'read_flag' => 0
                        )
                );
            }else{
                $this->MessagePair->id = $pair['MessagePair']['message_pair_id'];
                $pair                  = array(
                        'MessagePair' => array(
                                'last_sender_id' => $this->Auth->user('id'),
                                'last_receiver_id' => $to_user['User']['id'], 'read_flag' => 0
                        )
                );
            }
            if(!$this->MessagePair->save($pair)){
                $this->MessagePair->rollback();
                $this->Session->setFlash('送信できませんでした。恐れ入りますが再度お試しください。');
                return $this->redirect('/messages/view/'.$user_id);
            }
            $message                               = $this->request->data;
            $message['Message']['message_pair_id'] = $pair_id;
            $message['Message']['from_id']         = $this->Auth->user('id');
            $message['Message']['to_id']           = $user_id;
            if($this->Message->save($message)){
                $this->MessagePair->commit();
                $this->Session->setFlash('メッセージを送信しました');
                //メール通知（相手に通知）
                $this->send_mail($to_user['User'], $this->Auth->user(), $message['Message']['content']);
                return $this->redirect('/messages/view/'.$user_id);
            }else{
                $this->Session->setFlash('メッセージが送信できませんでした。恐れ入りますが、再度お試しください。');
            }
        }else{
            if($pair){
                $options        = array(
                        'conditions' => array('Message.message_pair_id' => $pair_id,),
                        'order' => array('Message.created' => 'DESC'), 'limit' => 30
                );
                $this->paginate = $options;
                $this->set('messages', $this->paginate('Message'));
                //未読フラグを外す
                if($pair['MessagePair']['last_sender_id'] != $this->Auth->user('id')){
                    $this->MessagePair->finish_read($pair_id);
                }
            }else{
                $this->set('messages', null);
            }
        }
    }

    /**
     * メッセージ送信時のメール通知関数
     */
    private function send_mail($to_user, $from_user, $content)
    {
        $url = h(Router::url('/', true)).'messages/view/'.$from_user['id'];
        $this->Mail->messaged($to_user, $from_user, $content, $url);
    }

}
