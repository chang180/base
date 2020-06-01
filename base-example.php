<?php
/* 
$dsn="mysql:host=localhost;charset=utf8;dbname=invoice";
$pdo=new PDO($dsn,'root',""); */
date_default_timezone_set("Asia/Taipei");
session_start();

class DB{
    private $dsn="mysql:host=localhost;charset=utf8;dbname=invoice";
    private $pdo;
    private $table;

    public function __construct($table){
        $this->table=$table;
        $this->pdo=new PDO($this->dsn,'root','');
    }

    //查詢全部
function all(...$arg){
    $sql="select * from $this->table ";
    
    if(isset($arg[0]) && is_array($arg[0])){
        $tmp=[];
        foreach($arg[0] as $key => $value){
            $tmp[]=sprintf("`%s`='%s'",$key,$value);
        }
        $sql=$sql." where " . implode(" && ",$tmp);
    }

    if(isset($arg[1])){
        $sql=$sql . $arg[1];
    }

    //echo $sql;

    return $this->pdo->query($sql)->fetchAll();

}

//刪除資料
function del($arg){
    $sql="delete from $this->table ";
    
    if(is_array($arg)){
        $tmp=[];
        foreach($arg as $key => $value){
            $tmp[]=sprintf("`%s`='%s'",$key,$value);
        }
        $sql=$sql." where " . implode(" && ",$tmp);
    }else{
        $sql=$sql." where `id`='$arg'";
    }

   // echo $sql;
    return $this->pdo->exec($sql);
}

//查詢單筆
function find($arg){

    $sql="select * from $this->table ";

    if(is_array($arg)){
        $tmp=[];
        foreach($arg as $key => $value){
            $tmp[]=sprintf("`%s`='%s'",$key,$value);
        }
        $sql=$sql." where " . implode(" && ",$tmp);
    }else{
        $sql=$sql." where `id`='$arg'";
    }

    //echo $sql;

    return $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
}

//計算筆數
function nums(...$arg){

    $sql="select count(*) from ".$this->table." ";
    
    if(isset($arg[0]) && is_array($arg[0])){
        $tmp=[];
        foreach($arg[0] as $key => $value){
            $tmp[]=sprintf("`%s`='%s'",$key,$value);
        }
        $sql=$sql." where " . implode(" && ",$tmp);
    }

    if(isset($arg[1])){
        $sql=$sql . $arg[1];
    }

    //echo $sql;

    return $this->pdo->query($sql)->fetchColumn();
}

//萬用查詢
function q($sql){
    return $this->pdo->query($sql)->fetchAll();

}


//新增或更新資料
function save($arg){
    if(isset($arg['id'])){
        //update
        foreach($arg as $key => $value){
            if($key!='id'){
                $tmp[]=sprintf("`%s`='%s'",$key,$value);
            }
        }
    
        $sql="update ".$this->table." set ".implode(',',$tmp)." where `id`='".$arg['id']."'";
    }else{
        //insert

        $sql="insert into ".$this->table." (`".implode("`,`",array_keys($arg))."`) values('".implode("','",$arg)."')";
    }

    //echo $sql;
    return $this->pdo->exec($sql);
}


}


//頁面導向
function to ($url){
    header("location:".$url);
}


$inv=new DB("invoice");
$an=new DB('award_number');

$row=$inv->all(""," limit 5");
//$row=all('invoice',""," limit 5");
$ar=$an->find(2);
//$ar=find('award_number',2);

print_r($row);

echo "<hr>";
print_r($ar);

?>