<?php
    $top_id = $_POST['top_id'];
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
    require_once('../config.php');
    try {
        $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "  SELECT results.*, game.*
                    FROM results
                    INNER JOIN game ON results.games_id = game.id
                    WHERE results.person_id = $top_id
                ";

        $stmt = $db->query($query);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $response = array("results" => $results);
        $json_response = json_encode($response);

        echo $json_response;
        } catch (PDOExeption $e) {
            echo $e->getMessage();
        };
?>