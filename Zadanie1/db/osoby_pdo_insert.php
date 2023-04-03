<?php
require_once('../../../site9.configs/config.php');

echo "<h1>PDO pristup</h1>"; 

try {
    $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo 'Connected to database';
    
    $query = "INSERT INTO osoby (meno, vek, pohlavie, opis) VALUES ('Veronika', '19', 'Z', 'studentka')";
//----------------------------------------------------
    echo "<h2>insert:</h2>"; 
    
    $count = $db->exec($query); 
    echo $count.'<br>';    //pocet pridanych riadkov  
    
    $insertId = $db->lastInsertId();
    echo $insertId.'<br>';
//----------------------------------------------------        
    $db = null;
    }
catch(PDOException $e)
    {
    echo $e->getMessage();
    }


?>