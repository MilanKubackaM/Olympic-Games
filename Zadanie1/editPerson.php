<?php
    session_start();

    if(isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] == true){
        $shouldShow = true;
    } else {
        $shouldShow = false;
    }

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once('../config.php');
    if (!isset($_GET['id'])) {
        exit("id not exist");
    } 
    try {
        $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (!empty($_POST) && !empty($_POST['name'])) {
            var_dump($_POST);
            $sql = "UPDATE person SET name=?, surname=?, birth_day=?, birth_place=?, birth_country=? where id=?";
            $stmt = $db->prepare($sql);
            $success = $stmt->execute([$_POST['name'], $_POST['surname'], $_POST['birth_day'], $_POST['birth_place'], $_POST['birth_country'], intval($_POST['person_id'])]);
        }
        $query = "SELECT * FROM person where id=?";
        $stmt = $db->prepare($query);
        $stmt->execute([$_GET['id']]);
        $person = $stmt->fetch(PDO::FETCH_ASSOC);

        if(isset($_POST['del_results_id'])){
            $sql = "DELETE FROM results WHERE id=?";
            $stmt = $db->prepare($sql);
            $stmt->execute([intval($_POST['del_results_id'])]);
        }
        $query = "  SELECT results.*, game.*
                    FROM results 
                    JOIN game 
                    ON results.games_id = game.id 
                    WHERE results.person_id=?
                ";
        $stmt = $db->prepare($query);
        $stmt->execute([$_GET['id']]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        };
        
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
        <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    </head>

    <body>
        <script>
            function pridajUmiestnenie(){
                var tabulka = document.getElementById("tabulka");
                tabulka.innerHTML += "<form action='#' method='post'> <tr><td> <input type='text' name='placing' placeholder='Zadaj umiestnenie' class='form-control' id=''> </td><td> <input type='text' name='discipline' placeholder='Zadaj disciplinu' class='form-control' id='InputName'> </td><td> <input type='text' name='city' placeholder='Zadaj miesto konania' class='form-control' id='InputName'> </td><td> <button type='submit' onclick='addDiscipline()' id='addDiscipline' class='btn btn-success'>Pridaj</button></form> </td></tr></form>";
            }
            function addDiscipline(){
                <?php
                    require_once('../config.php');

                    if (!isset($_GET['id'])) {
                        exit("id not exist");
                    }
                ?>
            }
        </script>

        <div class="container-md">
            <h1>Admin panel</h1>
            <h2>Info o sportovcovi</h2>
        
            <form action="#" method="post">
                <input type="hidden" name="person_id" value="<?php echo $person['id']; ?>">
                <div class="mb-3">
                    <label for="InputName" class="form-label">Name:</label>
                    <input type="text" name="name" class="form-control" id="InputName" value="<?php echo $person['name']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="InputSurname" class="form-label">Surname:</label>
                    <input type="text" name="surname" class="form-control" id="InputSurname" value="<?php echo $person['surname']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="InputDate" class="form-label">birth day:</label>
                    <input type="date" name="birth_day" class="form-control" id="InputDate" value="<?php echo $person['birth_day']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="InputbrPlace" class="form-label">birth place:</label>
                    <input type="text" name="birth_place" class="form-control" id="InputBrPlace" value="<?php echo $person['birth_place']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="InputBrCountry" class="form-label">birth country:</label>
                    <input type="text" name="birth_country" class="form-control" id="InputBrCountry" value="<?php echo $person['birth_country']; ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Uprav</button>
                <button onclick="pridajUmiestnenie()" type="button" class="btn btn-primary">Pridaj umiestnenie</button>
            </form>
            <h2>Umiestnenia</h2>
            <table class="table">
                <thead>
                    <tr>
                        <td>Umiestnenie</td>
                        <td>discipline</td>
                        <td>OH</td>
                        <td>Akcia</td>
                    </tr>
                </thead>
                <tbody id="tabulka">
                    <?php 
                        foreach ($results as $result) {
                            echo '<tr><td>' . $result['placing'] . '</td><td>' . $result['discipline'] . '</td><td>' . $result['city'] . '</td><td>';
                            echo '<form action="#" method="post"><input type="hidden" name="del_result_id" value="' . $result['id'] . '"><button type="submit" class="btn btn-danger">Vymaz</button></form>';
                            echo '<form action="editPlacing.php?id='. $result['id'] .'" method="post"><input type="hidden" name="update_id" value="' . $result['id'] . '"><button type="submit" class="btn btn-primary">Edit</button></form>';
                            echo '</td></tr>';
                        }
                    ?>
                </tbody>
            </table> 
        </div>
    </body>
</html>

