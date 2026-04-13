<?php
App::uses('AppHelper', 'View/Helper');
App::uses('JqueryEngineHelper', 'View/Helper');

class MyJqueryEngineHelper extends JqueryEngineHelper
{

    public function request($url, $options = array())
    {
        $url     = html_entity_decode($this->url($url), ENT_COMPAT, Configure::read('App.encoding'));
        $options = $this->_mapOptions('request', $options);
        if(isset($options['data']) && is_array($options['data'])){
            $options['data'] = $this->_toQuerystring($options['data']);
        }
        $options['url'] = $url;
        if(isset($options['update'])){
            $wrapCallbacks = isset($options['wrapCallbacks']) ? $options['wrapCallbacks'] : true;
            $success       = '';
            if(isset($options['success']) && !empty($options['success'])){
                $success .= $options['success'];
            }
            $success .= $this->jQueryObject.'("'.$options['update'].'").html(data);';
            if(!$wrapCallbacks){
                $success = 'function (data, textStatus) {'.$success.'}';
            }
            $options['dataType'] = 'html';
            $options['success']  = $success;
            unset($options['update']);
            //追記
            if(isset($options['form'])){
                $form            = $this->jQueryObject.'("'.$options['form'].'").serialize()';
                $options['data'] = $form;
            }
            //追記終了
        }
        $callbacks = array(
                'success', 'error', 'beforeSend', 'complete'
        );
        if(!empty($options['dataExpression'])){
            $callbacks[] = 'data';
            unset($options['dataExpression']);
        }
        //追加
        if(!empty($options['form'])){
            $callbacks[] = 'data';
            unset($options['form']);
        }
        //追記終了
        $options = $this->_prepareCallbacks('request', $options);
        $options = $this->_parseOptions($options, $callbacks);
        return $this->jQueryObject.'.ajax({'.$options.'});';
    }
}