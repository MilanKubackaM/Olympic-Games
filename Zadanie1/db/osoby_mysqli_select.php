<?php
require('../../../site9.configs/config.php');

echo "<h1>Proceduralny pristup</h1>"; 

//$link = mysqli_connect($hostname,$username,$password,$dbname) or die("Error " . mysqli_connect_error($link));
//$link = mysqli_connect(HOSTNAME,USERNAME,PASSWORD,DBNAME) or die("Error " . mysqli_connect_error($link));
$link = mysqli_connect($dbconfig['hostname'],$dbconfig['username'],$dbconfig['password'],$dbconfig['dbname']) or die("Error " . mysqli_connect_error($link));
 
$query = "SELECT vek, meno FROM osoby ORDER BY meno";
$result = mysqli_query($link, $query);

echo "<h2>mysqli_fetch_row:</h2>"; 

$row = mysqli_fetch_row($result);
echo("$row[1] <br><br>");

while(list($vek,$meno) = mysqli_fetch_row($result)) 
  echo "$meno ($vek rokov) <br>"; 

mysqli_free_result($result);
//----------------------------------------------------
echo "<h2>mysqli_fetch_array:</h2>"; 
$result = mysqli_query($link, $query);
  
while($row = mysqli_fetch_array($result)) 
  { $meno = $row["meno"];
    $vek = $row["vek"];
    echo "$meno ($vek rokov) <br>"; }  

mysqli_free_result($result);
$result = mysqli_query($link, $query);

while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
  { $meno = $row["meno"];
    $vek = $row["vek"];
    echo "$meno ($vek rokov) <br>"; }

mysqli_free_result($result);
$result = mysqli_query($link, $query);

while($row = mysqli_fetch_array($result, MYSQLI_NUM)) 
  { $meno = $row[1];
    $vek = $row[0];
    echo "$meno ($vek rokov) <br>"; }

mysqli_free_result($result);
//----------------------------------------------------
echo "<h2>mysqli_fetch_object:</h2>"; 
$result = mysqli_query($link, $query);

while ($obj = mysqli_fetch_object($result)) 
    echo "$obj->meno ($obj->vek rokov) <br>"; 

mysqli_free_result($result);
//----------------------------------------------------
echo "<h2>mysqli_num_rows:</h2>"; 

$query = "SELECT meno FROM osoby WHERE vek > 18";
$result = mysqli_query($link, $query);
echo "V databáze sú ".mysqli_num_rows($result)." osoby staršie ako 18 rokov.";
mysqli_free_result($result);

$query = "UPDATE osoby SET vek = '22' WHERE vek > 18";
mysqli_query($link, $query);
echo "V databáze boli modifikované ".mysqli_affected_rows($link)." záznamy.";

//mysqli_free_result($result);
mysqli_close($link);

//=====================================================
echo "<h1>Objektovy pristup</h1>"; 

$mysqli = new mysqli($hostname,$username,$password,$dbname);

$query = "SELECT vek, meno FROM osoby ORDER BY meno";
$result = $mysqli->query($query);

$row = $result->fetch_row();
echo("$row[1] <br><br>");

while (list($vek,$meno) = $result->fetch_row()) 
  echo "$meno ($vek rokov) <br>"; 

$result->close();
//----------------------------------------------------
echo "<h2>fetch_array:</h2>"; 
$result = $mysqli->query($query);

while ($row = $result->fetch_array(MYSQLI_ASSOC)) 
  { $meno = $row["meno"];
    $vek = $row["vek"];
    echo "$meno ($vek rokov) <br>"; }

$result->close();
$result = $mysqli->query($query);

while ($row = $result->fetch_array(MYSQLI_NUM)) 
  { $meno = $row[1];
    $vek = $row[0];
    echo "$meno ($vek rokov) <br>"; }

$result->close();
//----------------------------------------------------
echo "<h2>fetch_object:</h2>"; 
$result = $mysqli->query($query);

while ($obj = $result->fetch_object())
    echo "$obj->meno ($obj->vek rokov) <br>"; 
    
$result->close();
$mysqli->close();
//----------------------------------------------------


?>