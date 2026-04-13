<?php
App::uses('AppController', 'Controller');

/**
 * Categories Controller
 */
class AdminCategoriesController extends AppController
{
    public $uses = array('Category', 'Setting', 'Area');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('hoge');
        $this->layout = 'admin';
    }

    /**
     * カテゴリ設定
     * カテゴリ種類を1つ使うか、２つ使うか選択
     * カテゴリ１(categoriesテーブル)とカテゴリ２（areasテーブル）の名称を登録
     */
    public function admin_setting()
    {
        if($this->request->is('post') || $this->request->is('put')){
            $this->Setting->id = 1;
            $d = $this->request->data['Setting'];
            if(empty($d['cat_type_num']) || empty($d['cat1_name']) || empty($d['cat2_name'])){
                return $this->Session->setFlash('全項目を入力してください');
            }
            if($this->Setting->save($this->request->data, true, array('cat_type_num', 'cat1_name', 'cat2_name'))){
                $this->Session->setFlash('カテゴリ設定を登録しました');
            }else{
                $this->Session->setFlash('登録に失敗しました。');
            }
        }else{
            $this->request->data = $this->Setting->findById(1);
        }
    }

    /**
     * カテゴリー一覧（cat1)
     */
    public function admin_index()
    {
        $this->set('categories', $this->Category->get_categories());
    }

    /**
     * エリア一覧（cat2）
     */
    public function admin_cat2()
    {
        $this->set('areas', $this->Area->get_areas());
    }

    /**
     * カテゴリー追加(cat1)
     */
    public function admin_add()
    {
        if($this->request->is('post') || $this->request->is('put')){
            $data                      = $this->request->data;
            $data['Category']['order'] = 1;
            $this->Category->create();
            if($this->Category->save($data)){
                $this->Session->setFlash('カテゴリー1を登録しました');
                $this->redirect('/admin/admin_categories');
            }else{
                $this->Session->setFlash('カテゴリー1が登録できませんでした。恐れ入りますが再度お試しください。');
            }
        }
    }

    /**
     * エリア追加(cat2)
     */
    public function admin_cat2_add()
    {
        if($this->request->is('post') || $this->request->is('put')){
            $data                      = $this->request->data;
            $data['Area']['order'] = 1;
            $this->Area->create();
            if($this->Area->save($data)){
                $this->Session->setFlash('カテゴリー2を登録しました');
                $this->redirect('/admin/admin_categories/cat2');
            }else{
                $this->Session->setFlash('カテゴリー2が登録できませんでした。恐れ入りますが再度お試しください。');
            }
        }
    }

    /**
     * カテゴリー編集(cat1)
     */
    public function admin_edit($category_id = null)
    {
        if(!$category_id){
            $this->redirect('/admin/admin_categories');
        }
        $category = $this->Category->findById($category_id);
        $this->set(compact('category'));
        if($this->request->is('post') || $this->request->is('put')){
            $this->Category->id = $category_id;
            if($this->Category->save($this->request->data, true, array('name', 'order'))){
                $this->Session->setFlash('カテゴリーを登録しました');
                $this->redirect('/admin/admin_categories');
            }else{
                $this->Session->setFlash('カテゴリーが登録できませんでした。恐れ入りますが再度お試しください。');
            }
        }else{
            $this->request->data = $category;
        }
    }

    /**
     * エリア編集(cat2)
     */
    public function admin_cat2_edit($area_id = null)
    {
        if(!$area_id){
            $this->redirect('/admin/admin_categories/cat2');
        }
        $area = $this->Area->findById($area_id);
        $this->set(compact('area'));
        if($this->request->is('post') || $this->request->is('put')){
            $this->Area->id = $area_id;
            if($this->Area->save($this->request->data, true, array('name', 'order'))){
                $this->Session->setFlash('カテゴリー2を登録しました');
                $this->redirect('/admin/admin_categories/cat2');
            }else{
                $this->Session->setFlash('カテゴリー2が登録できませんでした。恐れ入りますが再度お試しください。');
            }
        }else{
            $this->request->data = $area;
        }
    }

    /**
     * カテゴリー削除(cat1)
     */
    public function admin_delete($category_id = null)
    {
        if(!$category_id){
            $this->redirect('/admin/admin_categories');
        }
        $category = $this->Category->findById($category_id);
        //既に登録しているプロジェクトがある場合は、削除できない
        if($this->Category->use_check($category_id)){
            $this->Session->setFlash('既にプロジェクトで登録されているカテゴリーは削除できません。');
            $this->redirect('/admin/admin_categories');
        }
        if($this->request->is('post') || $this->request->is('put')){
            if($this->Category->delete($category_id)){
                $this->Session->setFlash('カテゴリーを削除しました');
                $this->redirect('/admin/admin_categories');
            }else{
                $this->Session->setFlash('カテゴリーが削除できませんでした。恐れ入りますが再度お試しください。');
            }
        }
    }

    /**
     * エリア削除(cat2)
     */
    public function admin_cat2_delete($area_id = null)
    {
        if(!$area_id){
            $this->redirect('/admin/admin_categories/cat2');
        }
        $area = $this->Area->findById($area_id);
        //既に登録しているプロジェクトがある場合は、削除できない
        if($this->Area->use_check($area_id)){
            $this->Session->setFlash('既にプロジェクトで登録されているカテゴリーは削除できません。');
            $this->redirect('/admin/admin_categories/cat2');
        }
        if($this->request->is('post') || $this->request->is('put')){
            if($this->Area->delete($area_id)){
                $this->Session->setFlash('カテゴリー2を削除しました');
                $this->redirect('/admin/admin_categories/cat2');
            }else{
                $this->Session->setFlash('カテゴリー2が削除できませんでした。恐れ入りますが再度お試しください。');
            }
        }
    }
}
