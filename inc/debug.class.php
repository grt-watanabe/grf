<?php

# Web画面用
class grtDebug
{
    function view($value=NULL){
        if($value == "") $value = "NULL";

        if( $this->var_hensu($value) !== false ) $hensu_name = "[".$this->var_hensu($value)."]";
        else $hensu_name = "";

        echo '
        <div style="border: 1px dotted gray; font-size: 9pt; font-family: monospace; color: Gray; padding: .5em; margin: 8px; background-color: #EEEEEE">
        <span style="font-weight: bold">';
        if(is_array($value)){
            echo "array view ".$hensu_name.":</span><br><pre>"; print_r($value); echo "</pre>";
        }else{
            echo "echo view ".$hensu_name.":</span><br>".$value;
        }
        echo '</div>';
    }
    function var_hensu(&$var_hensu_value) {
        foreach( $GLOBALS as $key => $value ){
            //if ( is_array($value) ) continue;
            if ( $value === $var_hensu_value ) {
                $var_hensu_name = $key;
                return $var_hensu_name;
            }
        }
        return false;
    }
}

# Linuxコンソール画面用
class grtDebugLinux
{
    function view($value=NULL){
        if($value == "") $value = "NULL";

        if( $this->var_hensu($value) !== false ) $hensu_name = "[".$this->var_hensu($value)."]";
        else $hensu_name = "";

        echo "\n#-------\n";
        if(is_array($value)){
            echo "array view ".$hensu_name.":\n<pre>"; print_r($value); echo "</pre>";
        }else{
            echo "echo view ".$hensu_name.":\n".$value;
        }
        echo "\n                             ======= \n";
    }
    function var_hensu(&$var_hensu_value) {
        foreach( $GLOBALS as $key => $value ){
            //if ( is_array($value) ) continue;
            if ( $value === $var_hensu_value ) {
                $var_hensu_name = $key;
                return $var_hensu_name;
            }
        }
        return false;
    }
}
?>