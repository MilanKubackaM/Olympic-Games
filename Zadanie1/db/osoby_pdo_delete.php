<?php
require_once('../../../site9.configs/config.php');

echo "<h1>PDO pristup</h1>"; 

try {
    $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo 'Connected to database'; echo "<br>";
    
    $idMax = $db->query("SELECT MAX(id) as maxID FROM osoby;")->fetch(PDO::FETCH_ASSOC)['maxID'];
    echo 'Max. ID v DB: '.$idMax.'<br><br>'; 
    
    $query = "DELETE FROM osoby WHERE id=$idMax";

//----------------------------------------------------
    echo "<h2>delete:</h2>"; 
    
    $count = $db->exec($query); 
    echo $count.'<br>';    //pocet zmazanych riadkov  
    

//----------------------------------------------------        
    $db = null;
    }
catch(PDOException $e)
    {
    echo $e->getMessage();
    }


?>