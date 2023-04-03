<?php
require_once('../../../site9.configs/config.php');

echo "<h1>PDO pristup</h1>"; 

try {
    $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo 'Connected to database';

//----------------------------------------------------
    echo "<h2>prepare - positional (?) placeholders:</h2>";     
    $query = "SELECT * FROM osoby WHERE opis = ? AND vek > ?";
    
    $stmt = $db->prepare($query);
    $opis="student";
    $vek=18;
    $stmt->execute([$opis, $vek]);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //print_r($users);  echo "<br><br>";
    
    foreach($users as $item) {
        print_r($item); echo "<br>";
    }     

//----------------------------------------------------    
    echo "<h2>prepare - named (:opis) placeholders:</h2>";     
    $query = "SELECT * FROM osoby WHERE opis = :opis AND vek > :vek";
    
    $stmt = $db->prepare($query);
    $opis="student";
    $vek=18;
    $stmt->execute(['opis' => $opis, 'vek' => $vek]);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach($users as $item) {
        print_r($item); echo "<br>";
    }     

//----------------------------------------------------
    echo "<h2>prepare - bind parameters:</h2>";     
    $query = "SELECT * FROM osoby WHERE opis = :opis AND vek > :vek";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':opis', $opis, PDO::PARAM_STR, 10);
    $stmt->bindParam(':vek', $vek, PDO::PARAM_INT);
    $opis="student";
    $vek=18;
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach($users as $item) {
        print_r($item); echo "<br>";
    }     


//----------------------------------------------------     
    $db = null;
    }
catch(PDOException $e)
    {
    echo $e->getMessage();
    }


?>