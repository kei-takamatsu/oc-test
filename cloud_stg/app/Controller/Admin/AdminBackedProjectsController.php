<?php
App::uses('AppController', 'Controller');

class AdminBackedProjectsController extends AppController
{
    public $uses = array('BackedProject', 'Project');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('hoge');
        $this->layout = 'admin';
    }

    /**
     * 手動で支援を追加する
     * @param int $pj_id
     */
    public function admin_add_back($pj_id)
    {
        $pj = $this->Project->findById($pj_id);
        if(! $pj || $pj['Project']['active'] != 'yes') $this->redirect('/');
        $this->set('pj', $pj['Project']);
        $this->set('levels', $this->_get_levels_of_pj($pj_id));

        if($this->request->is('post') || $this->request->is('put')){
            if(empty($this->request->data['BackedProject'])) $this->redirect('/');
            $this->Project->begin();
            $req = $this->request->data['BackedProject'];
            $bp = $this->_make_bp($pj_id, $req);

            if($this->Project->add_backed_to_project(array('amount' => $req['invest_amount']), $pj)){
                $level = array('BackingLevel' => array('id' => $req['backing_level_id']));
                if($this->BackedProject->BackingLevel->put_backing_level_now_count($level)){
                    if($this->BackedProject->save($bp)){
                        $this->Project->commit();
                        $this->Session->setFlash('登録しました。');
                        return $this->redirect('/admin/admin_projects/open_projects');
                    }
                }
            }
            $this->Session->setFlash('登録に失敗しました。');
            $this->Project->rollback();
        }
    }

    private function _make_bp($pj_id, $req)
    {
        return array(
            'BackedProject' => array(
                'user_id' => $this->Auth->user('id'),
                'invest_amount' => $req['invest_amount'],
                'project_id' => $pj_id,
                'status' => STATUS_KARIURIAGE,
                'manual_flag' => 1,
                'backing_level_id' => $req['backing_level_id']
            )
        );
    }

    private function _get_levels_of_pj($pj_id)
    {
        $levels = $this->BackedProject->BackingLevel->findAllByProjectId($pj_id);
        $list = array();
        foreach($levels as $l){
            $list[$l['BackingLevel']['id']] = $l['BackingLevel']['name'];
        }
        return $list;
    }


}
