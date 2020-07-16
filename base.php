<?php
session_start();
date_default_timezone_set("Asia/Taipei");

class DB{
    private $dsn="mysql:host=localhost;charset=utf8;dbname=test";
    private $root="root";
    private $password="";
    public function __construct($table){
        $this->table=$table;
        $this->pdo=new PDO($this->dsn,$this->root,$this->password);
    }

    public function all(...$arg){
        $sql="SELECT * FROM $this->table ";
        if(!empty($arg[0]) && is_array($arg[0])){
            foreach ($arg[0] as $k=>$v) $tmp[]="`$k`='$v'";
            $sql.=" WHERE ".implode(" && ",$tmp);
        }
        $sql.=$arg[1]??"";
        return $this->pdo->query($sql)->fetchAll();
    }
    public function count(...$arg){
        $sql="SELECT COUNT(*) FROM $this->table ";
        if(!empty($arg[0]) && is_array($arg[0])){
            foreach ($arg[0] as $k=>$v) $tmp[]="`$k`='$v'";
            $sql.=" WHERE ".implode(" && ",$tmp);
        }
        $sql.=$arg[1]??"";
        return $this->pdo->query($sql)->fetchColumn();
    }

    public function del($arg){
        $sql="DELETE FROM $this->table ";
        if(is_array($arg)){
            foreach($arg as $k=>$v) $tmp[]="`$k`='$v'";
            $sql.=" WHERE ".implode(" && ",$tmp);
        }else $sql.=" WHERE `id`='$arg'";
        return $this->pdo->exec($sql);
    }

    public function find($arg){
        $sql="SELECT * FROM $this->table ";
        if(is_array($arg)){
            foreach($arg as $k=>$v) $tmp[]="`$k`='$v'";
            $sql.=" WHERE ".implode(" && ",$tmp);
        }else $sql.=" WHERE `id`='$arg'";
        return $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    }

    public function q($sql){
        return $this->pdo->query($sql)->fetchAll();
    }

    public function save($arg){
        if(isset($arg['id'])){
            foreach ($arg as $k=>$v) $tmp[]="`$k`='$v'";
            $sql=sprintf("UPDATE %s SET %s WHERE `id`='%s'",$this->table,implode(",",$tmp),$arg['id']);
        }else $sql=sprintf("INSERT INTO %s (`%s`) VALUES ('%s')",$this->table,implode("`,`",array_keys($arg)),implode("','",$arg));
        return $this->pdo->exec($sql);
    }
}
function to($url){
    header("location:$url");
}


//test 測試
if(empty($_SESSION['test'])) to("test.php");
unset($_SESSION['test']);
echo "導頁成功";
echo "<hr>";
$Test= new DB('test');
echo "資料全撈<hr>";
print_r($Test->all());
echo "<hr>";
echo "加資料，改一筆<hr>";
$Test->save(['test'=>'test2']);
$Test->save(['test'=>'Hello!','id'=>1]);
print_r($Test->all());
echo "<hr>";
echo "刪資料<hr>";
$Test->del(['test'=>"test2"]);
print_r($Test->all());
echo "<hr>";
echo "計筆數<hr>";
echo $Test->count();
echo "<hr>";
echo "查一筆<hr>";
print_r($Test->find(1));
echo "<hr>沒報錯就都對了！";
