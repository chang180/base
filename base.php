<!-- 物件導向…… -->
<!-- 繼續用力練他30遍 -->
<?php

date_default_timezone_set("Asia/Taipei");
session_start();

class DB
{
    private $pdo;
    private $table;
    private $dsn = "mysql:host=localhost;charset=utf8;dbname=files";

    public function __construct($table)
    {
        $this->table = $table;
        $this->pdo = new PDO($this->dsn, 'root', '');
    }

    // search data
    function all(...$arg)
    {
        $sql = "SELECT * FROM $this->table ";
        if (!isset($arg[0]) && is_array($arg[0])) {
            foreach ($arg[0] as $key => $value) {
                $tmp[] = sprintf("`%s`='%s'", $key, $value);
            }
            $sql .= " WHERE " . implode(" && ", $tmp);
        }
        if (isset($arg[1])) $sql .= $arg[1];

        return $this->pdo->query($sql)->fetchAll();
    }

    // delete data
    function del($arg)
    {
        $sql = "DELETE FROM $this->table";

        if (is_array($arg)) {
            foreach ($arg as $key => $value) {
                $tmp[] = sprintf("`%s`='%s'", $key, $value);
            }
            $sql .= " WHERE " . implode(" && ", $tmp);
        } else $sql .= " WHERE id='" . $arg . "'";
        return $this->pdo->exec($sql);
    }

    // find specific data
    function find($arg)
    {
        $sql = "SELECT * FROM $this->table ";

        if (is_array($arg)) {

            foreach ($arg as $key => $value) {
                $tmp[] = sprintf("`%s`='%s'", $key, $value);
            }
            $sql .= " WHERE " . implode(" && ", $tmp);
        } else $sql .= " WHERE id='" . $arg . "'";
        return $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    }

    // count data numbers
    function nums(...$arg)
    {
        $sql = "SELECT COUNT(*) FROM $this->table ";
        if (isset($arg[0]) && is_array($arg[0])) {
            foreach ($arg[0] as $key => $value) {
                $tmp[] = sprintf("`%s`='%s'", $key, $value);
            }
            $sql .= " WHERE " . implode(" && ", $tmp);
        }
        if (isset($arg[1])) $sql .= $arg[1];
        return $this->pdo->query($sql)->fetchColumn();
    }

    // query
    function q($sql)
    {
        return $this->pdo->query($sql)->fetchAll();
    }

    //insert or update data
    function save($arg)
    {
        if (isset($arg['id'])) {
            foreach ($arg as $key => $value) {
                if ($key != 'id') {

                    $tmp[] = sprintf("`%s`='%s'", $key, $value);
                }
            }
            $sql = "UPDATE " . $this->table . " SET " . implode(',', $tmp) . " WHERE 'id=" . $arg['id'] . "'";
        } else $sql = "INSERT INTO " . $this->table . " (`" . implode("`,`", array_keys($arg)) . "`) VALUES ('" . implode("','", $arg) . "')";
        return $this->pdo->exec($sql);
    }

}

// direct
function to($url){
    header("location:".$url);
}




?>