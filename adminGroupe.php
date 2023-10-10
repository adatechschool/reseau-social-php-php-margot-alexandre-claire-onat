
<?php
// On prolonge la session
session_start();
// On teste si la variable de session existe et contient une valeur
if(empty($_SESSION['pseudo'])) 
{
  // Si inexistante ou nulle, on redirige vers le formulaire de login
  header('Location: ./signIn.php');
  exit();
};


$userid = $_SESSION['id'];
$groupeId = $_GET['id'] ;//$_GET['id'];

$mysqli = new mysqli("localhost", "root", "root", "voisinous");
if ($mysqli->connect_errno) {
    echo ("Échec de la connexion : " . $mysqli->connect_error);
    exit();
} else {
        $querySearchGroup = "SELECT * "
        . "FROM groupes "
        . "WHERE "
        . "id = '" . $groupeId . "'";
        $searchGroup = $mysqli->query($querySearchGroup);
        $resultGroup = $searchGroup->fetch_assoc();
    if(!empty ($_POST)){
    $name = $_POST["name"]== null ? $resultGroup["name"]:$_POST["name"];
    $description = $_POST["description"]== null ? $resultGroup["description"]:$_POST["description"];
    $localisation = $_POST["localisation"]== null ? $resultGroup["localisation"] : $_POST["localisation"];
    $latitude = $_POST['lat'] != 0 ? $_POST['lat'] : $resultGroup['latitude'];
    $longitude = $_POST['lon'] != 0 ? $_POST['lon'] : $resultGroup['longitude'];
    $photo = $_FILES['img']['name']==null ? $resultGroup['photo'] : $_FILES['img']['name'];
   

    // $queryCreateUser = "INSERT INTO Users (pseudo, mail, mdp, localisation, latitude, longitude, date, photo) "
    //     . "VALUES ("
    //     . "'" . $pseudo . "',"
    //     . "'" . $mail . "',"
    //     . "'" . $mdphash . "',"
    //     . "'" . $localisation
    //     . "', CURRENT_TIMESTAMP ,"
    //     . "'" . $photo . "');";

    // $createUser = $mysqli->query($queryCreateUser);
    //'INSERT INTO tenu_de_la_semaine (id,styliste,haut_1,haut_2,bas,chaussure) VALUES(?,?,?,?,?,?)'
    //: 'UPDATE tenu_de_la_semaine set styliste = ?,haut_1 = ?,haut_2 = ?,bas = ?,chaussure = ? WHERE id = ?';

    $querymodifyGroup = $mysqli->prepare("UPDATE groupes set name = ?,description = ?,localisation = ?,photo = ?,latitude = ?,longitude = ? WHERE id = ?");

    $querymodifyGroup->bind_param('ssisddi',$name, $description, $localisation, $photo, $latitude, $longitude, $groupeId);

    $modifyGroup = $querymodifyGroup->execute();

    move_uploaded_file($_FILES['img']['tmp_name'], 'uploads/users/' . basename($_FILES['img']['name']));
    header("Location: ./adminGroupe.php/?id=$groupeId");
    exit();
};
};
 

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
  integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
  crossorigin=""/>
  <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"
  integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="
  crossorigin=""></script>


  <!-- Load Esri Leaflet from CDN -->
  <script src="https://unpkg.com/esri-leaflet@2.4.1/dist/esri-leaflet.js"
  integrity="sha512-xY2smLIHKirD03vHKDJ2u4pqeHA7OQZZ27EjtqmuhDguxiUvdsOuXMwkg16PQrm9cgTmXtoxA6kwr8KBy3cdcw=="
  crossorigin=""></script>

  <!-- Load Esri Leaflet Geocoder from CDN -->
  <link rel="stylesheet" href="https://unpkg.com/esri-leaflet-geocoder@2.3.3/dist/esri-leaflet-geocoder.css"
    integrity="sha512-IM3Hs+feyi40yZhDH6kV8vQMg4Fh20s9OzInIIAc4nx7aMYMfo+IenRUekoYsHZqGkREUgx0VvlEsgm7nCDW9g=="
    crossorigin="">
  <script src="https://unpkg.com/esri-leaflet-geocoder@2.3.3/dist/esri-leaflet-geocoder.js"
    integrity="sha512-HrFUyCEtIpxZloTgEKKMq4RFYhxjJkCiF5sDxuAokklOeZ68U2NPfh4MFtyIVWlsKtVbK5GD2/JzFyAfvT5ejA=="
    crossorigin=""></script>
    

    <title>Voisinous</title>

    <link rel="stylesheet" href="./htmlcss/stylesheets/profilPage.css">

</head>

<body>
    <?php include("./htmlcss/navbar.php") ?> 

    <main>
        <section id="UserInfo">
            <h1><?php echo $resultGroup['name']?></h1>
       <ul>
        <?php
         echo "<li> name :" . $resultGroup['name'] ."</li>";
         echo "<li> description:" . $resultGroup['description'] ."</li>";
         echo "<li> localisation:" . $resultGroup['localisation'] ."</li>";
         ?>
        </ul>
        </section>
        <section id="UserPhoto">
        <?php
        echo "<img src='./uploads/users/".$resultGroup['photo']."'/>"
        ?>
       
        </section>

        <section id="createForm">
            <h1>Modifier Profil de <?php echo $resultGroup['name']?></h1>
            <form action="./adminGroupe.php/?id=<?php echo "$groupeId"?>" method="post" enctype="multipart/form-data">
                <div>
                    <input type="text" name="name" placeholder="nom du groupe" />
                </div>
                <div>
                    <input type="number" name="localisation" maxlength="5" placeholder="localisation" />
                </div>
                <div>
                    <input type="textarea" name="description" placeholder="modifier la description">
                </div>
                <div>
                    <input type="file" name="img" placeholder="votre la photo de profil du groupe">
                </div>
                <input type="submit" value="VALIDER" />

                <?php include("./localisation.php") ?> 
            </form>
</section>
            
         

</body>

</html>