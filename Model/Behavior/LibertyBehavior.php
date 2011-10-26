<?php

/**
 * LibertyBehavior
 *
 * @copyright Copyright (C) 2010 saku
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */
App::uses('Sanitize', 'Utility');

class LibertyBehavior extends ModelBehavior {
    const VERSION = '2.0';
    public $settings = array();
    private $_helpers = array();

    function setup(&$model, $config = array()) {
        $this->settings = $config;
    }


    /**
     * SQLを取得
     * ※値に対してpg_escape_stringします.
     * ※elements/sql以下のファイルを読みます
     *
     * @access public
     * @author kozo
     * @param  elementName エレメント名
     * @param  param エレメントに渡すパラメータ(キー：変数名、値：value)
     */
    public function getSQL(&$model, $elementName, $param = array()){

        $elementName = sprintf("sql/%s", $elementName);

        // エスケープする
        $escapeParam = $this->_escapeSQL($model, $param);

        return $this->getElementString($model, $elementName, $escapeParam, '.sql');
    }


    /**
     * SQLをエスケープする
     *
     * @access private
     * @author kozo
     * @param  param エレメントに渡すパラメータ(キー：変数名、値：value)
     */
    private function _escapeSQL(&$model, $param){
        // エスケープする
        $escapeParam = array();
        foreach($param as $key=>$value)
        {
            if(is_object($value) || empty($value)){
                // オブジェクトか空の場合は何も処理しない
                $escapeParam[$key] = $value;
                continue;
            }

            if(is_array($value)){
                // 配列の場合は再帰
                $escapeParam[$key] = $this->_escapeSQL($model, $value);
                continue;
            }

            // 通常はエスケープ
            $escapeParam[$key] = Sanitize::escape($value, $model->useDbConfig);
        }

        return $escapeParam;
    }

    /**
     * XMLを取得
     * ※ elements/xml以下のファイルを読みます
     * ※ short_open_tagをオフにしないと使えません。
     *
     * @access public
     * @author kozo
     * @param  elementName エレメント名
     * @param  param エレメントに渡すパラメータ(キー：変数名、値：value)
     */
    public function getXML(&$model, $elementName, $param = array()){

        $elementName = sprintf("xml/%s", $elementName);

        // エスケープする
        $escapeParam = $this->_escapeXML($model, $param);

        return $this->getElementString($model, $elementName, $escapeParam, '.xml');
    }

    /**
     * XMLをエスケープする
     *
     * @access private
     * @author kozo
     * @param  param エレメントに渡すパラメータ(キー：変数名、値：value)
     */
    private function _escapeXML(&$model, $param){
        // エスケープする
        $escapeParam = array();
        foreach($param as $key=>$value)
        {
            if(is_object($value) || empty($value)){
                // オブジェクトか空の場合は何も処理しない
                $escapeParam[$key] = $value;
                continue;
            }

            if(is_array($value)){
                // 配列の場合は再帰
                $escapeParam[$key] = $this->_escapeXML($model, $value);
                continue;
            }

            // 通常はエスケープ
            $escapeParam[$key] = Sanitize::html($value);
        }

        return $escapeParam;
    }

    /**
     * Livertyでヘルパーを使えるようにセットする
     * 
     * @access public
     * @author kozo
     * @param $helpers ヘルパー名の配列(controllerで指定するものと同じ)
     */
    public function setHelpers(&$model, $helpers){
        $this->_helpers = $helpers;
    }
    
    /**
     * elementから文字列を取得する
     *
     * @access public
     * @author kozo
     * @param  elementName エレメント名
     * @param  param エレメントに渡すパラメータ(キー：変数名、値：value)
     * @param  ext 拡張子
     * @return
     */
    public function getElementString(&$model, $elementName, $param = array(), $ext = ".ctp"){
        $dummy=null;
        if(class_exists('View')){
            $View = new View($dummy); 
        }else{
            App::uses('View', 'View');
            $View = new View($dummy);
        }
        
        if(!empty($this->_helpers)){
            // ヘルパーを読み込む
            $View->helpers = $this->_helpers;
        }

        $View->ext = $ext;
        $str = $View->element($elementName, $param);

        return $str;
    }
}
?>