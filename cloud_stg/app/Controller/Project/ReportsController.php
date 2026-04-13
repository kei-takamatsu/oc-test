<?php
App::uses('AppController', 'Controller');

class ReportsController extends AppController
{
    public $uses = array(
            'Report', 'User', 'Project', 'ReportContent'
    );

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow(array('view'));
        if($this->action === 'edit_detail'
           || $this->action === 'edit'
        ){
            $this->Security->validatePost = false;
            $this->Security->csrfUseOnce  = false;
            $this->Security->csrfCheck    = false;
        }
    }

    /**
     * 活動報告一覧（マイページ）
     * 自分のプロジェクトの活動報告を全て一覧で表示する（最新順）
     * @return void
     */
    public function index()
    {
        $this->layout   = 'mypage';
        $this->paginate = $this->Report->get_all_reports_options_of_user($this->auth_user['User']['id'], 10);
        $this->set('reports', $this->paginate('Report'));
    }

    /**
     * 活動報告追加（マイページ）
     * プロジェクトとタイトルを入力して、活動報告データを作成する
     * 作成後に活動報告詳細登録画面に移動
     */
    public function add($project_id = null)
    {
        $this->layout = 'mypage';
        //project_idが引数にある場合、viewのプロジェクト選択で事前セレクトする
        $this->set(compact('project_id'));
        $projects = $this->Project->get_pj_of_user($this->auth_user['User']['id'], 'list', 100, -1);
        if(!$projects){
            $this->Session->setFlash('まだ公開しているプロジェクトがありません');
            $this->redirect('/mypage');
        }
        $this->set(compact('projects'));
        if($this->request->is('post') || $this->request->is('put')){
            //project id チェック
            if(!in_array($this->request->data['Report']['project_id'], array_keys($projects))){
                $this->redirect('/');
            }
            $this->Ring->bindUp('Report');
            $this->Report->create();
            if($this->Report->save($this->request->data, true, array(
                    'id', 'project_id', 'title', 'created', 'modified'
            ))
            ){
                $this->redirect(array(
                        'action' => 'edit_detail', $this->Report->field('id')
                ));
            }else{
                $this->Session->setFlash('活動報告が登録できませんでした。恐れ入りますが再度お試しください。');
            }
        }
    }

    /**
     * 活動報告編集（基本情報の編集画面）
     * @param int $report_id
     * @return void
     */
    public function edit($report_id = null)
    {
        $this->layout = 'mypage';
        //活動報告の編集権限チェック
        $report = $this->edit_roll_check($report_id, $this->auth_user['User']['id']);
        if($this->request->is('post') || $this->request->is('put')){
            $this->Report->id                    = $report_id;
            $this->request->data['Report']['id'] = $report_id;
            if(!empty($this->request->params['form']['thumbnail'])){
                $this->request->data['Report']['thumbnail'] = $this->request->params['form']['thumbnail'];
            }else{
                $this->request->data['Report']['thumbnail'] = null;
            }
            $this->Ring->bindUp('Report');
            $this->autoRender = false;
            if($this->Report->save($this->request->data, true, array(
                    'title', 'modified', 'thumbnail'
            ))
            ){
                return json_encode(array(
                        'status' => 1, 'msg' => '活動報告を修正しました'
                ));
            }else{
                return json_encode(array(
                        'status' => 0, 'msg' => '活動報告を登録できませんでした。恐れ入りますが再度お試し下さい'
                ));
            }
        }else{
            $this->request->data = $report;
        }
    }

    /**
     * 活動報告編集権限チェック関数
     */
    private function edit_roll_check($report_id, $user_id)
    {
        $report = $this->Report->edit_roll_check($report_id, $user_id);
        if(!$report){
            $this->redirect('/');
        }
        $this->set(compact('report'));
        return $report;
    }

    /**
     * 活動報告編集（詳細情報の編集画面）
     * @param null $report_id
     */
    public function edit_detail($report_id = null)
    {
        $this->layout = 'mypage';
        //活動報告の編集権限チェック
        $this->edit_roll_check($report_id, $this->auth_user['User']['id']);
        //report_contentの取得
        $report_contents = $this->ReportContent->get_contents($report_id);
        $this->set(compact('report_contents'));
    }

    /**
     * 活動報告の公開関数
     */
    public function open($report_id)
    {
        $this->edit_roll_check($report_id, $this->auth_user['User']['id']);
        $result = $this->Report->open($report_id);
        switch($result[0]){
            case 1:
                $this->Session->setFlash('活動報告を公開しました');
                break;
            case 2:
                $this->Session->setFlash('活動報告が公開できませんでした。恐れ入りますが再度お試しください');
                break;
            case 3:
                $this->Session->setFlash($result[1]);
        }
        return $this->redirect($this->referer());
    }

    /**
     * 活動報告の非公開関数
     */
    public function close($report_id)
    {
        $this->edit_roll_check($report_id, $this->auth_user['User']['id']);
        if($this->Report->close($report_id)){
            $this->Session->setFlash('活動報告を非公開にしました');
        }else{
            $this->Session->setFlash('活動報告が非公開にできませんでした。恐れ入りますが再度お試しください');
        }
        return $this->redirect($this->referer());
    }

    /**
     * 経過報告削除
     * @param int $report_id
     * @return void
     */
    public function delete($report_id = null)
    {
        $report = $this->edit_roll_check($report_id, $this->auth_user['User']['id']);
        $this->Report->begin();
        if($this->Report->delete($report_id)){
            $report_cnt = $this->Report->get_report_number_of_project($report['Report']['project_id']);
            if($this->Project->save_report_cnt($report['Report']['project_id'], $report_cnt)){
                $this->Report->commit();
                $this->Session->setFlash('活動報告を削除しました');
                return $this->redirect($this->referer());
            }
        }
        $this->Report->rollback();
        $this->Session->setFlash('活動報告が削除できませんでした。恐れ入りますが再度お試しください');
        return $this->redirect($this->referer());
    }

    /**
     * 経過報告一覧（管理画面）
     * @return void
     */
    public function admin_index()
    {
        $this->layout            = 'admin';
        $this->Report->recursive = 0;
        $this->set('Reports', $this->paginate());
    }

    /**
     * 経過報告編集（管理画面）
     * @param string $id
     * @return void
     */
    public function admin_edit($id = null)
    {
        $this->layout     = 'admin';
        $this->Report->id = $id;
        if(!$this->Report->exists()){
            $this->redirect('/');
        }
        if($this->request->is('post') || $this->request->is('put')){
            if($this->Report->save($this->request->data)){
                $this->Session->setFlash('経過報告を更新しました');
                $this->redirect(array('action' => 'index'));
            }else{
                $this->Session->setFlash('経過報告が更新できませんでした');
            }
        }else{
            $this->request->data = $this->Report->read(null, $id);
        }
        $projects = $this->Report->Project->find('list');
        $this->set(compact('projects'));
    }

    /**
     *　経過報告削除（管理画面）
     * @param string $id
     * @return void
     */
    public function admin_delete($id = null)
    {
        if(!$this->request->is('post')){
            $this->redirect('/');
        }
        $this->Report->id = $id;
        if(!$this->Report->exists()){
            $this->redirect('/');
        }
        if($this->Report->delete()){
            $this->Session->setFlash('経過報告を削除しました');
        }else{
            $this->Session->setFlash('経過報告を削除できませんでした');
        }
        $this->redirect(array('action' => 'index'));
    }
}
