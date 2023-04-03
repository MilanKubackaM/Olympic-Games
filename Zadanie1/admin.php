<?php
    session_start();
        if(isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] == true){
            $shouldShow = true;
        } else {
            $shouldShow = false;
        }


    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("Location: ./login/login.php");
        exit;
    }

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

    } catch (PDOException $e) {
        echo $e->getMessage();
    }

    if(!empty($_POST) && !empty($_POST['name'])){
        $sql = "INSERT INTO person (name, surname, birth_day, birth_place, birth_country) VALUES (?,?,?,?,?)";
        $stmt = $db->prepare($sql);
        $success = $stmt->execute([$_POST['name'], $_POST['surname'], $_POST['birth_day'], $_POST['birth_place'], $_POST['birth_country']]);
    }

    if(!empty($_POST) && !empty($_POST['person_id'])){
        $sql = "DELETE FROM person WHERE id=?";
        $stmt = $db->prepare($sql);
        $success = $stmt->execute([intval($_POST['person_id'])]);
    }

    $query = "SELECT * FROM person";
    $stmt = $db->query($query); 
    $delete_persons = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Olympionics</title>

        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/jquery.dataTables.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,700' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="./mystyle.css">
        <link rel="stylesheet" href="./style.css">
    </head>

    <body>
        <script src="stranka/js/jquery.min.js"></script>
        <script src="stranka/js/popper.js"></script>
        <script src="stranka/js/bootstrap.min.js"></script>
        <script src="stranka/js/main.js"></script>
        <section class="ftco-section">
            <div class="container">
                <nav class="navbar navbar-expand-lg ftco_navbar ftco-navbar-light" id="ftco-navbar">
                    <div class="container">
                        <a class="navbar-brand" href="index.php">Úprava záznamov</a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="fa fa-bars"></span> Menu
                        </button>
                        <div class="collapse navbar-collapse" id="ftco-nav">
                            <ul class="navbar-nav ml-auto mr-md-3">
                            <li class="nav-item"><a href="./index.php" class="nav-link">Domov</a></li>
                            <li class="nav-item active"><a href="./admin.php" class="nav-link">Admin</a></li> 
                            <li class="nav-item" style="display:<?php echo $shouldShow ? 'block' : 'none'; ?>"><a href="./index.php" class="nav-link"><?php echo $_SESSION["fullname"]; ?></a></li>
                            <li class="nav-item" style="display:<?php echo $shouldShow ? 'block' : 'none'; ?>"><a href="./login/logout.php" class="nav-link">Logout </a></li>
                            <li class="nav-item" style="display:<?php echo $shouldShow ? 'none' : 'block'; ?>"><a href="./login/login.php" class="nav-link">Login </a></li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        </section>
        
        <div class="entirePageContainer">
            <div class="container-md row">
                <div class="leftSide col-sm">
                    <form action="#" method="post">
                        <div class="mb-3">
                            <label for="InputName" class="form-label">Meno:</label>
                            <input type="text" name="name" class="form-control" id="InputName" required>
                        </div>
                        <div class="mb-3">
                            <label for="InputSurname" class="form-label">Priezvisko:</label>
                            <input type="text" name="surname" class="form-control" id="InputSurname" required>
                        </div>
                        <div class="mb-3">
                            <label for="InputDate" class="form-label">Datum narodenia:</label>
                            <input type="date" name="birth_day" class="form-control" id="InputDate" required>
                        </div>
                        <div class="mb-3">
                            <label for="InputbrPlace" class="form-label">Krajina narodenia:</label>
                            <input type="text" name="birth_place" class="form-control" id="InputBrPlace" required>
                        </div>
                        <div class="mb-3">
                            <label for="InputBrCountry" class="form-label">Mesto narodenia:</label>
                            <input type="text" name="birth_country" class="form-control" id="InputBrCountry" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Pridaj</button>
                    </form>
                </div>

                <div class="rightSide col-sm">
                    <form action="#" method="post">
                        <select name="person_id">
                            <?php
                                foreach($delete_persons as $person){
                                    echo '<option value="' . $person['id'] . '">' . $person['name'] . ' ' . $person['surname'] . '</option>';
                                }       
                            ?>
                        </select>
                        <button type="submit" class="btn btn-danger">Odstran</button>
                    </form>

                    <table class="table">
                    <thead>
                        <tr><td>Meno</td><td>Priezvisko</td><td>Narodenie</td></tr>
                    </thead>
                    <tbody>
                        <?php 
                            foreach($persons as $person){
                                $date = new DateTimeImmutable($person["birth_day"]);
                                echo "<tr><td><a href='editPerson.php?id=" .  $person["id"] . "'>" . $person["name"] . "</a></td><td>" . $person["surname"] . "</td><td>" . $date->format("d.m.Y") . "</td></tr>";
                            }
                        ?> 
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>
