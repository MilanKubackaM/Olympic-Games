<?php
require_once('../../../site9.configs/config.php');

echo "<h1>PDO pristup</h1>"; 

try {
    //$db = new PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8", $username, $password);     
    $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo 'Connected to database';
    
    $query = "SELECT vek, meno FROM osoby ORDER BY meno";
//----------------------------------------------------
    echo "<h2>fetch_assoc:</h2>"; 
    
    $stmt = $db->query($query); 
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo $row['meno'].'<br><br>';      

    $stmt = $db->query($query); 
    while($row = $stmt->fetch(PDO::FETCH_ASSOC))    //PDO::FETCH_NUM, PDO::FETCH_BOTH
    { echo "{$row['meno']} ( {$row['vek']} rokov)<br>"; }
    echo "<br>";
    
    foreach($db->query($query) as $row) 
    { echo "{$row['meno']} ( {$row['vek']} rokov)<br>"; }
    //{ echo $row['meno']." (".$row['vek']." rokov)<br>"; }
    echo "<br>";  

    $stmt = $db->query($query);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($results);
//----------------------------------------------------
    echo "<h2>fetch_object:</h2>"; 

    $stmt = $db->query($query);   
    while ($obj = $stmt->fetch(PDO::FETCH_OBJ))
    { echo "$obj->meno ($obj->vek rokov) <br>"; }  

//----------------------------------------------------
    echo "<h2>rowCount:</h2>"; 
    
    $stmt = $db->query($query);
    $row_count = $stmt->rowCount();
    echo 'Riadky v select-e: '.$row_count;    
//----------------------------------------------------        
    $db = null;
    }
catch(PDOException $e)
    {
    echo $e->getMessage();
    }


?>