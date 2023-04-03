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
    try {
        $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "SELECT * FROM person";
        $stmt = $db->query($query); 
        $persons = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        echo $e->getMessage();
    }

    $query = "  SELECT *
                FROM results
                WHERE results.id=" . $_GET['id'];
    $stmt = $db->query($query); 
    $placing = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
            <script src="../stranka/js/popper.js"></script>
            <script src="../stranka/js/bootstrap.min.js"></script>
            <script src="../stranka/js/main.js"></script>
            <section class="ftco-section">
                <div class="container">
                    <nav class="navbar navbar-expand-lg ftco_navbar ftco-navbar-light" id="ftco-navbar">
                        <div class="container">
                            <a class="navbar-brand" href="index.php">Edit umiestenia</a>
                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="fa fa-bars"></span> Menu
                            </button>
                            <div class="collapse navbar-collapse" id="ftco-nav">
                                <ul class="navbar-nav ml-auto mr-md-3">
                                <li class="nav-item"><a href="./index.php" class="nav-link">Domov</a></li>
                                <li class="nav-item"><a href="./admin.php" class="nav-link">Admin</a></li> 
                                <li class="nav-item active"><a href="./editPlacing.php" class="nav-link">Editplacing</a></li>
                                <li class="nav-item" style="display:<?php echo $shouldShow ? 'block' : 'none'; ?>"><a href="./index.php" class="nav-link"><?php echo $_SESSION["fullname"]; ?></a></li>
                                <li class="nav-item" style="display:<?php echo $shouldShow ? 'block' : 'none'; ?>"><a href="./login/logout.php" class="nav-link">Logout </a></li>
                                <li class="nav-item" style="display:<?php echo $shouldShow ? 'none' : 'block'; ?>"><a href="./login/login.php" class="nav-link">Login </a></li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>
            </section>
        
        <form action="#" method="post" style="margin: 1em 16em 1em 16em">
            <input type="hidden" name="person_id" value="<?php echo $person['id']; ?>">
            <div class="mb-3">
                <label for="InputName" class="form-label">Umiestnenie:</label>
                <input type="numebr" name="name" class="form-control" id="number" value="<?php echo $placing[0]['placing']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="InputSurname" class="form-label">Disciplina:</label>
                <input type="text" name="surname" class="form-control" id="InputSurname" value="<?php echo $placing[0]['discipline']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary" name="uprav">Uprav</button>
            <button type="submit" class="btn btn-danger" name="vymaz">Vymaz</button>
        </form>
    </body>
</html>
