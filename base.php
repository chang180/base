<!-- 本日測試精簡可短至幾行 -->
<?php
session_start();
date_default_timezone_set("Asia/Taipei");
class DB
{
    private $table;
    private $pdo;
    private $dsn = "mysql:host=localhost;charset=utf8;dbname=db02";
    private $root = "root";
    private $password = "";
    public function __construct($table)
    {
        $this->table = $table;
        $this->pdo = new PDO($this->dsn, $this->root, $this->password);
    }

    public function all(...$arg)
    {
        $sql = "SELECT * FROM $this->table ";
        if (!empty($arg[0]) && is_array($arg[0])) {
            foreach ($arg[0] as $key => $value) $tmp[] = "`$key`='$value'";
            $sql .= " WHERE " . implode(" && ", $tmp);
        }
        if (!empty($arg[1])) $sql .= $arg[1];
        return $this->pdo->query($sql)->fetchAll();
    }

    public function del($arg)
    {
        $sql = "DELETE FROM $this->table ";
        if (is_array($arg)) {
            foreach ($arg as $key => $value) $tmp[] = "`$key`='$value'";
            $sql .= " WHERE " . implode(" && ", $tmp);
        } else $sql .= " WHERE `id`='$arg'";
        return $this->pdo->exec($sql);
    }

    public function find($arg)
    {
        $sql = "SELECT * FROM $this->table ";
        if (is_array($arg)) {
            foreach ($arg as $key => $value) $tmp[] = "`$key`='$value'";
            $sql .= " WHERE " . implode(" && ", $tmp);
        } else $sql .= " WHERE `id`='$arg'";
        return $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    }

    public function count(...$arg){
        $sql="SELECT COUNT(*) FROM $this->table ";
        if(is_array($arg[0])){
            foreach($arg[0] as $key =>$value) $tmp[]="`$key`='$value'";
            $sql.=" WHERE ".implode(" && ",$tmp);
        }
        if(!empty($arg[1])) $sql.=$arg[1];
        return $this->pdo->query($sql)->fetchColumn();
    }

    public function q($sql)
    {
        return $this->pdo->query($sql)->fetchAll();
    }

    public function save(...$arg)
    {
        if (isset($arg['id'])) {
            foreach ($arg as $key => $value) $tmp[] = "`$key`='$value'";
            //update
            $sql = sprintf("UPDATE %s WHERE `id`='%s'", implode(",", $tmp), $arg['id']);
            //insert
        }
        $sql = sprintf("INSERT INTO %s (`%s`) VALUES ('%s')", $this->table, implode("`,`", array_keys($arg)), implode("','", $arg));
        return $this->pdo->exec($sql);
    }
}

function to($url)
{
    header("location:" . $url);
}



?>