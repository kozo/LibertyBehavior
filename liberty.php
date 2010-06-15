<?php
class LibertyBehavior extends ModelBehavior { 
    var $settings = array();
    
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
        $escapeParam = array();
        foreach($param as $key=>$value)
        {
            $escapeParam[$key] = pg_escape_string($value);
        }
        
        return $this->getElementString($model, $elementName, $escapeParam, '.sql');
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
        
        return $this->getElementString($model, $elementName, $param, '.xml');
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
        $view = new view($dummy);
        
        $view->ext = $ext;
        $str = $view->element($elementName, $param);
        
        return $str;
    }
} 
?>