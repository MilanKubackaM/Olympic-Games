<?php
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);

    require_once '../../config.php';
    require_once 'PHPGangsta/GoogleAuthenticator.php';
    try {
        $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
    } catch (PDOExeption $e) {
        echo $e->getMessage();
    };

    function checkEmpty($field) {
        if (empty(trim($field))) {
            return true;
        }
        return false;
    }

    function checkLength($field, $min, $max) {
        $string = trim($field);   
        $length = strlen($string); 
        if ($length < $min || $length > $max) {
            return false;
        }
        return true;
    }

    function checkUsername($username) {
        if (!preg_match('/^[a-zA-Z0-9_]+$/', trim($username))) {
            return false;
        }
        return true;
    }

    function checkGmail($email) {
        if (!preg_match('/^[\w.+\-]+@gmail\.com$/', trim($email))) {
            return false;
        }
        return true;
    }

    function userExist($db, $login, $email) {
        $exist = false;
        $param_login = trim($login);
        $param_email = trim($email);

        $sql = "SELECT id FROM users WHERE login = :login OR email = :email";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":login", $param_login, PDO::PARAM_STR);
        $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $exist = true;
        }
        unset($stmt);
        return $exist;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $errmsg = "";
        if (checkEmpty($_POST['login']) === true) {
            $errmsg .= "<p>Zadajte login.</p>";
        } elseif (checkLength($_POST['login'], 6,32) === false) {
            $errmsg .= "<p>Login musi mat min. 6 a max. 32 znakov.</p>";
        } elseif (checkUsername($_POST['login']) === false) {
            $errmsg .= "<p>Login moze obsahovat iba velke, male pismena, cislice a podtrznik.</p>";
        }
        if (userExist($pdo, $_POST['login'], $_POST['email']) === true) {
            $errmsg .= "Pouzivatel s tymto e-mailom / loginom uz existuje.</p>";
        }
        if (checkGmail($_POST['email'])) {
            $errmsg .= "Prihlaste sa pomocou Google prihlasenia";
        }
        if (empty($errmsg)) {
            $sql = "INSERT INTO users (fullname, login, email, password, 2fa_code) VALUES (:fullname, :login, :email, :password, :2fa_code)";

            $fullname = $_POST['firstname'] . ' ' . $_POST['lastname'];
            $email = $_POST['email'];
            $login = $_POST['login'];
            $hashed_password = password_hash($_POST['password'], PASSWORD_ARGON2ID);

            $g2fa = new PHPGangsta_GoogleAuthenticator();
            $user_secret = $g2fa->createSecret();
            $codeURL = $g2fa->getQRCodeGoogleUrl('Olympic Games', $user_secret);

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":fullname", $fullname, PDO::PARAM_STR);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->bindParam(":login", $login, PDO::PARAM_STR);
            $stmt->bindParam(":password", $hashed_password, PDO::PARAM_STR);
            $stmt->bindParam(":2fa_code", $user_secret, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $qrcode = $codeURL;
            } else {
                echo "Ups. Nieco sa pokazilo";
            }
            unset($stmt);
        }
        unset($pdo);
    }

?>

<!doctype html>
<html lang="sk">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
            content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Login/register s 2FA - Register</title>

        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/jquery.dataTables.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,700' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="../mystyle.css">
        <link rel="stylesheet" href="../style.css">

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
    </head>
    <body>
        <header>
            <hgroup>
                <h1 style="margin: 1em 5em 1em 5em">Registracia</h1>
            </hgroup>
        </header>
        <main style="margin: 2em 6em 2em 6em">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <div class="form-outline mb-2">
                    <input id="form2Example1" class="form-control"  type="text" name="firstname" value="" id="firstname" placeholder="napr. Jonatan" required>
                    <label for="firstname" class="form-label" for="form2Example1" value="" id="firstname" placeholder="napr. Jonatan">Meno</label>
                </div>

                <div class="form-outline mb-2">
                <input id="form2Example1" class="form-control" type="text" name="lastname" value="" id="lastname" placeholder="napr. Petrzlen" required>
                    <label for="firstname" class="form-label" for="form2Example1" value="" id="firstname" placeholder="napr. Jonatan">Priezvisko</label>
                </div>

                <div class="form-outline mb-2">
                <input id="form2Example1" class="form-control" type="email" name="email" value="" id="email" placeholder="napr. jpetrzlen@example.com" required>
                    <label for="firstname" class="form-label" for="form2Example1" value="" id="firstname" placeholder="napr. Jonatan">Email</label>
                </div>

                <div class="form-outline mb-2">
                <input id="form2Example1" class="form-control" type="text" name="login" value="" id="login" placeholder="napr. jperasin" required">
                    <label for="firstname" class="form-label" for="form2Example1" value="" id="firstname" placeholder="napr. Jonatan">Login</label>
                </div>

                <div class="form-outline mb-2">
                <input id="form2Example1" class="form-control" type="password" name="password" value="" id="password" required>
                    <label for="password" class="form-label" for="form2Example2">Heslo</label>
                </div>

                <button type="submit" class="btn btn-success btn-block mb-4">Vytvorit konto</button>
                <?php
                    if (!empty($errmsg)) {
                        echo $errmsg;
                    }
                    if (isset($qrcode)) {
                        $message = '<p>Naskenujte QR kod do aplikacie Authenticator pre 2FA: <br><img src="'.$qrcode.'" alt="qr kod pre aplikaciu authenticator"></p>';
                        echo $message;
                        echo '<p>Teraz sa mozte prihlasit: <a href="login.php" role="button">Login</a></p>';
                    }
                ?>
            </form>
            <p>Mate vytvorene konto? <a href="login.php">Prihlaste sa tu.</a></p>
        </main>
    </body>
</html>