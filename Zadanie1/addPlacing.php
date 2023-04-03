<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once('../config.php');
    try {
        $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "SELECT * FROM person";
        $stmt = $db->query($query); 
        $persons = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach($persons as $person){
            echo $person;
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
?>