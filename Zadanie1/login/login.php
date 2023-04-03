<?php

session_start();

    if(isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] == true){
        $shouldShow = true;
    } else {
        $shouldShow = false;
    }

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: restricted.php");
    exit;
}

require_once "../../config.php";
require_once 'PHPGangsta/GoogleAuthenticator.php';
try {
    $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOExeption $e) {
    echo $e->getMessage();
};

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql = "SELECT fullname, email, login, password, created_at, 2fa_code FROM users WHERE login = :login";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":login", $_POST["login"], PDO::PARAM_STR);

    if ($stmt->execute()) {
        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch();
            $hashed_password = $row["password"];
            if (password_verify($_POST['password'], $hashed_password)) {
                $g2fa = new PHPGangsta_GoogleAuthenticator();
                if ($g2fa->verifyCode($row["2fa_code"], $_POST['2fa'], 2)) {
                    $_SESSION["loggedin"] = true;
                    $_SESSION["login"] = $row['login'];
                    $_SESSION["fullname"] = $row['fullname'];
                    $_SESSION["email"] = $row['email'];
                    $_SESSION["created_at"] = $row['created_at'];
                    header("location: ../index.php");
                }
                else {
                    echo "Neplatny kod 2FA.";
                }
            } else {
                echo "Nespravne meno alebo heslo.";
            }
        } else {
            echo "Nespravne meno alebo heslo.";
        }
    } else {
        echo "Ups. Nieco sa pokazilo!";
    }
    unset($stmt);
    unset($pdo);
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <style>
            html {
                max-width: 70ch;
                padding: 3em 1em;
                margin: auto;
                line-height: 1.75;
                font-size: 1.25em;
            }

            h1,h2,h3,h4,h5,h6 {
                margin: 3em 0 1em;
            }

            p,ul,ol {
                margin-bottom: 2em;
                color: #1d1d1d;
                font-family: sans-serif;
            }
        </style>
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
        <link rel="stylesheet" href="../mystyle.css">
        <link rel="stylesheet" href="../style.css">
    </head>
    <body>
        <script src="../stranka/js/jquery.min.js"></script>
        <script src="../stranka/js/popper.js"></script>
        <script src="../stranka/js/bootstrap.min.js"></script>
        <script src="../stranka/js/main.js"></script>
        <section class="ftco-section">
            <div class="container">
                <nav class="navbar navbar-expand-lg ftco_navbar ftco-navbar-light" id="ftco-navbar">
                    <div class="container">
                        <a class="navbar-brand" href="index.php">Prihlásenie</a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="fa fa-bars"></span> Menu
                        </button>
                        <div class="collapse navbar-collapse" id="ftco-nav">
                            <ul class="navbar-nav ml-auto mr-md-3">
                                <li class="nav-item active"><a href="../index.php" class="nav-link">Domov</a></li>
                            <li class="nav-item"><a href="../admin.php" class="nav-link">Admin</a></li> 
                            <li class="nav-item" style="display:<?php echo $shouldShow ? 'block' : 'none'; ?>"><a href="../index.php" class="nav-link"><?php echo $_SESSION["fullname"]; ?></a></li>
                            <li class="nav-item" style="display:<?php echo $shouldShow ? 'block' : 'none'; ?>"><a href="../login/logout.php" class="nav-link">Logout </a></li>
                            <li class="nav-item" style="display:<?php echo $shouldShow ? 'none' : 'block'; ?>"><a href="../login/login.php" class="nav-link">Login </a></li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        </section>
        <main style="margin: 2em 6em 2em 6em">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <div class="form-outline mb-4">
                    <input type="text" name="login" value="" id="login" id="form2Example1" class="form-control" />
                    <label for="login" class="form-label" for="form2Example1">Meno</label>
                </div>

                <div class="form-outline mb-4">
                    <input type="password" class="form-control" name="password" value="" id="password" required/>
                    <label for="password" class="form-label" for="form2Example2">Heslo</label>
                </div>

                <div class="form-outline mb-4">
                    <input type="number" id="form2Example2" class="form-control" name="2fa" value="" id="2fa"required/>
                    <label for="2fa" class="form-label" for="form2Example2">Dvojfaktorove overenie</label>
                </div>

                <div class="row mb-4">
                    <div class="col d-flex justify-content-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="form2Example31" checked />
                        <label class="form-check-label" for="form2Example31"> Remember me </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block mb-4">Prihlasit sa</button>

                <div class="text-center">
                    <p>Nemate ucet? <a href="register.php">Zaregistrujte sa!</a></p>
                    <p>alebo sa prihlaste:</p>
                    <a href="./PHPGangsta/GoogleAuthenticator.php" class="btn">
                        <img src="https://www.google.com/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png" alt="Google logo" width="100">
                    </a>
                </div>
            </form>
        </main>
    </body>
</html>