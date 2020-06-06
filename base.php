<!-- 練習時腦袋要清醒一點 -->
<!-- 最好是能專心再練，但更好的是能邊打瞌睡也做得出來 -->
<?php
session_start();
date_default_timezone_set(("Asia/Taipei"));

class DB
{
    private $table;
    private $dsn = "mysql:host=localhost;charset=utf8;dbname=invoice";
    private $pdo;
    private $root = "root";
    private $password = "";
    public function __construct($table)
    {
        $this->table = $table;
        $this->pdo = new PdO($this->dsn, $this->root, $this->password);
    }
    // search data
    public function all(...$arg)
    {
        $sql = "SELECT * FROM $this->table ";
        if (isset($arg[0]) && is_array($arg[0])) {
            foreach ($arg[0] as $key => $value) {
                $tmp[] = sprintf("`%s='%s", $key, $value);
            }
            $sql .= " WHERE " . implode(" && ", $tmp);
        }
        if (isset($arg[1])) $sql .= $arg[1];
        return $this->pdo->query($sql)->fetchAll();
    }

    // delete data
    public function del($arg)
    {
        $sql = "DELETE FROM $this->table ";
        if (is_array($arg)) {
            foreach ($arg as $key => $value) {
                $tmp[] = sprintf("`%s`='%s", $key, $value);
            }
            $sql .= " WHERE " . implode(" && ", $tmp);
        } else $sql .= " WHERE id='$arg'";
        return $this->pdo->exec($sql);
    }

    // search specific data
    public function find($arg)
    {
        $sql = "SELECT * FROM $this->table ";
        if (is_array($arg)) {
            foreach ($arg as $key => $value) {
                $tmp[] = sprintf("`%s`='%s'", $key, $value);
            }
            $sql .= " WHERE " . implode(" && ", $tmp);
        } else $sql .= " WHERE id='$arg'";
        return $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    }

    // count data numbers
    public function count(...$arg)
    {
        $sql = "SELECT COUNT(*) FROM $this->table ";
        if (isset($arg[0])) {
            foreach ($arg[0] as $key => $value) {
                $tmp[] = sprintf("`%s`='%s'", $key, $value);
            }
            $sql .= " WHERE " . implode(" && ", $tmp);
        }
        if (isset($arg[1])) $sql .= $arg[1];
        return $this->pdo->query($sql)->fetchColumn();
    }

    //query
    public function q($sql)
    {
        return $this->pdo->query($sql)->fetchAll();
    }

    //insert or update data
    public function save($arg)
    {
        if (isset($arg['id]'])) {
            foreach ($arg as $key => $value) {
                $tmp[] = sprintf("`%s`='%s'", $key, $value);
            }
            //update
            $sql = "UPDATE $this->table SET " . implode(",", $tmp) . " WHERE id='" . $arg['id'] . "'";
            //insert
        } else $sql = "INSERT INTO $this->table SET (`" . implode("`,`", array_keys($arg)) . "`) VALUES ('" . implode("','", $arg) . "')";
        return $this->pdo->exec($sql);
    }
}
// direct
function to($url)
{
    header("location:" . $url);
}








$inv = new DB("invoice");
$an = new DB('award_number');

$row = $inv->all("", " limit 5");
//$row=all('invoice',""," limit 5");
$ar = $an->find(2);
//$ar=find('award_number',2);

print_r($row);

echo "<hr>";
print_r($ar);

?>