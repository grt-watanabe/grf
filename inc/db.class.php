<?php

# constants used by class
define('MYSQL_TYPES_NUMERIC', 'int real ');
define('MYSQL_TYPES_DATE',    'datetime timestamp year date time ');
define('MYSQL_TYPES_STRING',  'string blob ');

class grtDB
{
    var $last_error;         // holds the last error. Usually mysql_error()
    var $last_query;         // holds the last query executed.
    var $row_count;          // holds the last number of rows from a select

    var $db_link;            // current/last database link identifier
    var $auto_slashes;       // the class will add/strip slashes when it can

    # DB接続情報
    function db_class($db_select) {
        switch ($db_select) {
            case "localdb1":
                $this->host = 'localhost';
                $this->user = 'root';
                $this->pass = '';
                //$this->dbnm = 'test';
                //$this->dbnm = 'test1';
                break;

            case "localdb2":
                $this->host = 'localhost';
                $this->user = 'root';
                $this->pass = '';
                //$this->dbnm = 'test2';
                break;
        }
        $this->auto_slashes = true;
    }

    # DB接続
    function connect($db_select, $persistant=false) {
        $this->db_class($db_select);

        # Establish the connection.
        if ($persistant) {
            $this->db_link = mysql_pconnect($this->host, $this->user, $this->pass);
        }else{
            $this->db_link = mysql_connect($this->host, $this->user, $this->pass);
        }

        # Check for an error establishing a connection
        if (!$this->db_link) {
            $this->last_error = mysql_error();
            return false;
        }
        return $this->db_link;  // success
    }

    # DB選択
    function select_db($db='', $link) {
        if (!empty($db)) $this->dbnm = $db;

        if (!mysql_select_db($this->dbnm, $link)) {
            $this->last_error = mysql_error();
            return false;
        }
        mysql_query("SET NAMES utf8");
        return true;
    }

    # トランザクション開始
    function begine() {
        $r = mysql_query("START TRANSACTION");
        if (!$r) {
            $this->last_error = mysql_error();
            return false;
        }
        return true;
    }

    # コミット
    function commit() {
        $r = mysql_query("COMMIT");
        if (!$r) {
            $this->last_error = mysql_error();
            return false;
        }
        return true;
    }

    # ロールバック
    function rollback() {
        $r = mysql_query("ROLLBACK");
        if (!$r) {
            $this->last_error = mysql_error();
            return false;
        }
        return true;
    }

    # DB接続終了
    function close() {
        mysql_close($this->db_link);
        return true;
    }

    function select($sql) {
        $this->last_query = $sql;

        $r = $this->mysql_catchquery($sql);
        if (!$r) {
            $this->last_error = mysql_error();
            return false;
        }
        $this->row_count = mysql_num_rows($r);
        return $r;
    }

    function select_one($sql) {
        $this->last_query = $sql;

        $r = $this->mysql_catchquery($sql);
        if (!$r) {
            $this->last_error = mysql_error();
            return $r;
        }
        if (mysql_num_rows($r) > 1) {
            $this->last_error = "Your query in function select_one() returned more that one result.";
            return false;
        }
        if (mysql_num_rows($r) < 1) {
            $this->last_error = "Your query in function select_one() returned no results.";
            return false;
        }
        $ret = mysql_result($r, 0);
        mysql_free_result($r);

        if ($this->auto_slashes) return stripslashes($ret);
        else return $ret;
    }

    function mysql_catchquery($query){
        if( $result = mysql_query($query) ) return $result;
        else throw new Exception( mysql_error() );
    }

    function get_row($result, $type=NULL) {
        if (!$result) {
            $this->last_error = "Invalid resource identifier passed to get_row() function.";
            return false;
        }

        switch($type){
            case 'NULL':
                $row = mysql_fetch_array($result, MYSQL_ASSOC);
                break;
            case 1:
                $row = mysql_fetch_array($result, MYSQL_NUM);
                break;
            default:
                $row = mysql_fetch_array($result, MYSQL_BOTH);
        }

        if (!$row) return false;
        if ($this->auto_slashes) {
            # strip all slashes out of row...
            foreach ($row as $key => $value) {
                $row[$key] = stripslashes($value);
            }
        }
        return $row;
    }

    function get_rows($result, $type=NULL) {
        if (!$result) {
            $this->last_error = "Invalid resource identifier passed to get_row() function.";
            return false;
        }

        switch($type){
            case 1:
                $mode = MYSQL_NUM;
                break;
            case 2:
                $mode = MYSQL_BOTH;
                break;
            default:
                $mode = MYSQL_ASSOC;
        }
        $rows = $this->get_rows_value($result, $mode);

        if ( $rows === NULL ) return NULL;
        if ($this->auto_slashes) {
            # strip all slashes out of row...
            foreach ($rows as $key => $value) {
                foreach ($value as $key2 => $value2) {
                    $rows[$key][$key2] = stripslashes($value2);
                }
            }
        }
        return $rows;
    }

    function get_rows_value($result, $mode){
        $i = 0;
        $return_values = array();
        while( $rows = mysql_fetch_array($result, $mode) ){
            $return_values[$i] = $rows;
            $i++;
        }
        if( count($return_values) < 1 ) $return_values = NULL;
        return $return_values;
    }

    function dump_query($sql) {
        $r = $this->select($sql);
        if (!$r) return false;
        echo "<div style=\"border: 1px dotted blue; font-family: sans-serif; margin: 8px;\">\n";
        echo "<table cellpadding=\"3\" cellspacing=\"1\" border=\"0\" width=\"100%\">\n";

        $i = 0;
        while ($row = mysql_fetch_assoc($r)) {
            if ($i == 0) {
                echo "<tr><td colspan=\"" . sizeof($row) . "\"><span style=\"font-face: monospace; font-size: 9pt;\">$sql</span></td></tr>\n";
                echo "<tr>\n";
                foreach ($row as $col => $value) {
                    echo "<td bgcolor=\"#E6E5FF\"><span style=\"font-face: sans-serif; font-size: 9pt; font-weight: bold;\">$col</span></td>\n";
                }
                echo "</tr>\n";
            }
            $i++;
            if ($i % 2 == 0) $bg = '#E3E3E3';
            else $bg = '#F3F3F3';
            echo "<tr>\n";
            foreach ($row as $value) {
                echo "<td bgcolor=\"$bg\"><span style=\"font-face: sans-serif; font-size: 9pt;\">$value</span></td>\n";
            }
            echo "</tr>\n";
        }
        echo "</table></div>\n";
    }

    function insert_sql($sql) {
        $this->last_query = $sql;

        $r = $this->mysql_catchquery($sql);
        if (!$r) {
            $this->last_error = mysql_error();
            return $r;
        }

        $id = mysql_insert_id();
        if ($id == 0) return true;
        else return $id;
    }

    function update_sql($sql) {
        $this->last_query = $sql;

        $r = $this->mysql_catchquery($sql);
        if (!$r) {
            $this->last_error = mysql_error();
            return $r;
        }

        $rows = mysql_affected_rows();
        if ($rows == 0) return true; // no rows were updated
        else return $rows;
    }

    function insert_array($table, $data) {
        if (empty($data)) {
            $this->last_error = "You must pass an array to the insert_array() function.";
            return false;
        }

        $cols = '(';
        $values = '(';

        foreach ($data as $key => $value) { // iterate values to input

            $cols .= "$key,";

            $col_type = $this->get_column_type($table, $key); // get column type
            if (!$col_type) return false;  // error!

            # determine if we need to encase the value in single quotes
            if (is_null($value)) {
                $values .= "NULL,";
            } elseif (substr_count(MYSQL_TYPES_NUMERIC, "$col_type ")) {
                $values .= "$value,";
            } elseif (substr_count(MYSQL_TYPES_DATE, "$col_type ")) {
                //$value = $this->sql_date_format($value, $col_type); // format date
                $values .= "'$value',";
            } elseif (substr_count(MYSQL_TYPES_STRING, "$col_type ")) {
                if ($this->auto_slashes) $value = addslashes($value);
                    $values .= "'$value',";
            }
        }
        $cols   = rtrim($cols, ',').')';
        $values = rtrim($values, ',').')';

        # insert values
        $sql = "INSERT INTO $table $cols VALUES $values";
        return $this->insert_sql($sql);

    }

    function update_array($table, $data, $condition) {
        if (empty($data)) {
            $this->last_error = "You must pass an array to the update_array() function.";
            return false;
        }

        $sql = "UPDATE $table SET";
        foreach ($data as $key=>$value) {     // iterate values to input

            $sql .= " $key=";

            $col_type = $this->get_column_type($table, $key);  // get column type
            if (!$col_type) return false;  // error!

            # determine if we need to encase the value in single quotes
            if (is_null($value)) {
                $sql .= "NULL,";
            } elseif (substr_count(MYSQL_TYPES_NUMERIC, "$col_type ")) {
                $sql .= "$value,";
            } elseif (substr_count(MYSQL_TYPES_DATE, "$col_type ")) {
                //$value = $this->sql_date_format($value, $col_type); // format date
                $sql .= "'$value',";
            } elseif (substr_count(MYSQL_TYPES_STRING, "$col_type ")) {
            if ($this->auto_slashes) $value = addslashes($value);
                $sql .= "'$value',";
            }

        }
        $sql = rtrim($sql, ','); // strip off last "extra" comma
        if (!empty($condition)) $sql .= " WHERE $condition";

        # insert values
        return $this->update_sql($sql);
    }

    function execute_file ($file) {
        if (!file_exists($file)) {
            $this->last_error = "The file $file does not exist.";
            return false;
        }
        $str = file_get_contents($file);
        if (!$str) {
            $this->last_error = "Unable to read the contents of $file.";
            return false;
        }

        $this->last_query = $str;

        # split all the query's into an array
        $sql = explode(';', $str);
        foreach ($sql as $query) {
            if (!empty($query)) {
                $r = mysql_query($query);

                if (!$r) {
                    $this->last_error = mysql_error();
                    return false;
                }
            }
        }
        return true;

    }

    function get_column_type($table, $column) {
        $r = mysql_query("SELECT $column FROM $table");
        if (!$r) {
            $this->last_error = mysql_error();
            return false;
        }
        $ret = mysql_field_type($r, 0);
        if (!$ret) {
            $this->last_error = "Unable to get column information on " . $table . $column;
            mysql_free_result($r);
            return false;
        }
        mysql_free_result($r);
        return $ret;

    }

    function sql_date_format($value) {
        if (gettype($value) == 'string') $value = strtotime($value);
        return date('Y-m-d H:i:s', $value);
    }

    function error_view($show_query=true) {
        echo "<div style=\"border: 1px dotted red; font-size: 9pt; font-family: monospace; color: red; padding: .5em; margin: 8px; background-color: #FFE2E2\">";
        echo "<span style=\"font-weight: bold\">db.class.php Error:</span><br>" . $this->last_error."</div>";

        if ($show_query && (!empty($this->last_query))) {
            $this->query_view();
        }
    }

    function error_view_linux($show_query=true) {
        echo "\n#error_view:: " . $this->last_error . "\n";
        if( $show_query && (!empty($this->last_query)) ){
            $this->query_view();
        }
    }

    function query_view() {
        echo "<div style=\"border: 1px dotted blue; font-size: 9pt; font-family: monospace; color: blue; padding: .5em; margin: 8px; background-color: #E6E5FF\">";
        echo "<span style=\"font-weight: bold\">Last SQL Query:</span><br>".str_replace("\n", "<br>", $this->last_query)."</div>";
    }

    function query_view_linux() {
        echo "\n#query_view:: ". $this->last_query. "\n";
    }

}

?>