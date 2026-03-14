<?php

require_once __DIR__ . "/../../core/Database.php";

class Vehicle {

    private $conn;

    public function __construct(){

        $db = new Database();

        $this->conn = $db->conn;
    }

public function getCarById($id){

$sql = "SELECT * FROM vehicles WHERE id=?";

$stmt = $this->conn->prepare($sql);

$stmt->bind_param("i",$id);

$stmt->execute();

$result = $stmt->get_result();

return $result->fetch_assoc();

}

public function getAllCars(){

$sql = "SELECT * FROM vehicles";

$result = $this->conn->query($sql);

$cars = [];

while($row = $result->fetch_assoc()){
$cars[] = $row;
}

return $cars;

}

public function search($data){

$sql="SELECT * FROM vehicles WHERE 1=1";

$params=[];
$types="";

/* location */

if(!empty($data['location'])){

$sql.=" AND location LIKE ?";

$params[]="%".$data['location']."%";

$types.="s";

}

/* price */

/* price range */

if(!empty($data['price'])){

$range=$data['price'][0];

list($min,$max)=explode("-",$range);

$sql.=" AND price_per_day BETWEEN ? AND ?";

$params[]=$min;
$params[]=$max;

$types.="ii";

}

/* seats */

if(!empty($data['seats'])){

$seats=$data['seats'][0];

$sql.=" AND seats=?";

$params[]=$seats;

$types.="i";

}

/* brand */

if(!empty($data['brand'])){

$brands=$data['brand'];

$placeholders=implode(",",array_fill(0,count($brands),"?"));

$sql.=" AND branch IN ($placeholders)";

foreach($brands as $b){

$params[]=$b;

$types.="s";

}

}

$stmt=$this->conn->prepare($sql);

if(!empty($params)){
$stmt->bind_param($types,...$params);
}

$stmt->execute();

$result=$stmt->get_result();

$cars=[];

while($row=$result->fetch_assoc()){

$cars[]=$row;

}

return $cars;

}

public function filter($data){

$sql="SELECT * FROM vehicles WHERE 1=1";

$params=[];
$types="";

/* price */

if(!empty($data['price'])){

$sql.=" AND price<=?";

$params[]=$data['price'];
$types.="i";

}

/* seats */

if(!empty($data['seats'])){

$sql.=" AND seats=?";

$params[]=$data['seats'];
$types.="i";

}

/* brand */

if(!empty($data['brand'])){

$sql.=" AND brand=?";

$params[]=$data['brand'];
$types.="s";

}

$stmt=$this->conn->prepare($sql);

if(!empty($params)){
$stmt->bind_param($types,...$params);
}

$stmt->execute();

$result=$stmt->get_result();

$cars=[];

while($row=$result->fetch_assoc()){
$cars[]=$row;
}

return $cars;

}
}