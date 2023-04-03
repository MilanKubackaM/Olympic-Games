<?php
require_once('../../../site9.configs/config.php');

echo "<h1>PDO pristup</h1>"; 

try {
    $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo 'Connected to database';
    
    $data = array(
    0 => array(
        'meno' => 'Marek',
        'vek' => '20',
        'pohlavie' => 'M',
        'opis' => 'student'
    ),
    1 => array(
        'meno' => 'Matej',
        'vek' => '24',
        'pohlavie' => 'M',
        'opis' => 'student'
    ),);
//----------------------------------------------------
    echo "<h2>prepare - insert:</h2>";     
    
    $query = "INSERT INTO osoby (meno, vek, pohlavie, opis) VALUES (:meno, :vek, :pohlavie, :opis)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':meno', $meno);
    $stmt->bindParam(':vek', $vek);
    $stmt->bindParam(':pohlavie', $pohlavie);
    $stmt->bindParam(':opis', $opis);
   
    foreach ($data as $item) {
        $meno = $item['meno'];
        $vek = $item['vek'];
        $pohlavie = $item['pohlavie'];
        $opis = $item['opis'];
        $stmt->execute();
    }

    $pocet = count($data);
    echo "$pocet";
    
//----------------------------------------------------        
    $db = null;
    }
catch(PDOException $e)
    {
    echo $e->getMessage();
    }


?>