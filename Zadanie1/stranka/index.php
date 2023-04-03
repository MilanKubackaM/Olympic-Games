


<?php
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);

    require_once('../config.php');
    try {
        $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "SELECT * 
                  FROM person 
                  INNER JOIN results 
                  ON person.id = results.person_id
                  INNER JOIN  game
                  ON results.games_id = game.id";
        $stmt = $db->query($query);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $query = "SELECT person.name, person.surname, person.id, COUNT(results.placing) AS total_placings
                  FROM person
                  JOIN results ON person.ID = results.person_id
                  WHERE results.placing = 1
                  GROUP BY person.ID
                  ORDER BY total_placings DESC
                  LIMIT 10";
          
        $stmt = $db->query($query);
        $resultOfTops = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOExeption $e) {
        echo $e->getMessage();
    };
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
    <link rel="stylesheet" href="./mystyle.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,700' rel='stylesheet' type='text/css'>

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
<section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-6 text-center mb-5">
					<h2 class="heading-section">Website menu #04</h2>
				</div>
			</div>
		</div>
		<div class="container">
			<nav class="navbar navbar-expand-lg ftco_navbar ftco-navbar-light" id="ftco-navbar">
		    <div class="container">
		    	<a class="navbar-brand" href="index.html">Digital</a>
		    	<div class="social-media order-lg-last">
		    		<p class="mb-0 d-flex">
		    			<a href="#" class="d-flex align-items-center justify-content-center"><span class="fa fa-facebook"><i class="sr-only">Facebook</i></span></a>
		    			<a href="#" class="d-flex align-items-center justify-content-center"><span class="fa fa-twitter"><i class="sr-only">Twitter</i></span></a>
		    			<a href="#" class="d-flex align-items-center justify-content-center"><span class="fa fa-instagram"><i class="sr-only">Instagram</i></span></a>
		    			<a href="#" class="d-flex align-items-center justify-content-center"><span class="fa fa-dribbble"><i class="sr-only">Dribbble</i></span></a>
		    		</p>
	        </div>
		      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
		        <span class="fa fa-bars"></span> Menu
		      </button>
		      <div class="collapse navbar-collapse" id="ftco-nav">
		        <ul class="navbar-nav ml-auto mr-md-3">
		        	<li class="nav-item active"><a href="#" class="nav-link">Home</a></li>
		        	<li class="nav-item"><a href="#" class="nav-link">About</a></li>
		        	<li class="nav-item"><a href="#" class="nav-link">Work</a></li>
		        	<li class="nav-item"><a href="#" class="nav-link">Blog</a></li>
		          <li class="nav-item"><a href="#" class="nav-link">Contact</a></li>
		        </ul>
		      </div>
		    </div>
		  </nav>
    <!-- END nav -->
  </div>

	</section>

	<script src="js/jquery.min.js"></script>
  <script src="js/popper.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/main.js"></script>

	</body>
    <div id="mainTableContainer" style="margin: 2%">
        <table id="myTable" class="cell-border display" width="100%">
            <thead>
                <tr>
                    <th>Meno</th>
                    <th>Priezvisko</th>
                    <th>Narodenie</th>
                    <th>Rok získania medialy</th>
                    <th>Krajina konania</th>
                    <th>Mesto konania</th>
                    <th>Typ olympiády</th>
                    <th>Disciplína</th>
                </tr>
            </thead>
            <tbody>
            <?php 
                foreach($results as $result){
                    echo "<tr><td>" . 
                    $result["name"] . "</td><td>" . 
                    $result["surname"] . "</td><td>" . 
                    $result["birth_day"] . "</td><td>".
                    $result["year"] . "</td><td>" . 
                    $result["country"] . "</td><td>" . 
                    $result["city"] . "</td><td>" . 
                    $result["type"] . "</td><td>" . 
                    $result["discipline"] . "</td></tr>" ;
                } 
                ?>
            </tbody>
        </table>
    </div>
    
    <div class="topTableContainer" style="margin: 10%">
        <table id="resultOfTops" class="cell-border display compact" width="100%">
            <thead>
                <tr>
                    <th>Počet zlatých medailí</th>
                    <th>Meno</th>
                    <th>Priezvisko</th> 
                    <th>ID</th>
                </tr>
            </thead>
            <tbody>
            <?php 
                foreach($resultOfTops as $resultOfTop){
                    echo "<tr><td>" . 
                    $resultOfTop["total_placings"] . "</td><td>" . 
                    $resultOfTop["name"] . "</td><td>" . 
                    $resultOfTop["surname"] . "</td><td>" .
                    $resultOfTop["id"] . "</td></tr>" ;

                } 
                ?>
            </tbody>
        </table>
    </div>
    
    <script> 
        $(document).ready(function() {
            $('#myTable').DataTable();  

            var table = $('#resultOfTops').DataTable().order([0, 'desc']).draw();
            var data = {};
            
            $('#resultOfTops').on('click', 'tbody tr', function () {
                const top_id = table.row(this).data()[3];

                $.ajax({
                    url: "readJson.php",
                    method: "post",
                    data: {top_id : JSON.stringify(top_id)},
                    success: function(res){
                        data = JSON.parse(res);
                        openModal(data.results);
                    }
                });
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            var modal = document.getElementById("myModal");
            var span = document.getElementsByClassName("close")[0];

            window.openModal = function(data) {
                console.log(data);
                document.getElementById("content").innerHTML = "";

                let i = 0;
                while(data[i]){
                    document.getElementById("content").innerHTML += (
                        "<b>Disciplina: </b>" + data[i].discipline + "  " +
                        "<b>Miesto konania: </b>" + data[i].country + "  " +
                        "<b>Rok konania: </b>" + data[i].year + "  " +
                        "<b>Typ hier: </b>" + data[i].type + "  " +
                        "<b>Umiestnenie: </b>" + data[i].placing + "<br><br>"
                    );
                    console.log(i);
                    i++;
                };
                modal.style.display = "block";
            }

            span.onclick = function() {
                modal.style.display = "none";
            }   
        });
    </script>

    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Prehlad</h3>
            <div id="content"> </div>
        </div>
    
    </div>
</body>
</html>

