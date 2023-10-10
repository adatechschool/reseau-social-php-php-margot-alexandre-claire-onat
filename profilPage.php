<?php
include("./sessionprolong.php");
$userid = $_SESSION['id'];

$mysqli = new mysqli("localhost", "root", "root", "voisinous");
if ($mysqli->connect_errno) {
    echo ("Échec de la connexion : " . $mysqli->connect_error);
    exit();
} else {
        $querySearchUser = "SELECT * "
        . "FROM users "
        . "WHERE "
        . "id = '" . $userid . "'";
        $searchUser = $mysqli->query($querySearchUser);
        $resultUser = $searchUser->fetch_assoc();
 if(!empty ($_POST)){
    
    $pseudo = $resultUser["pseudo"];
    $mail = $resultUser["mail"];
    $mdphash = $_POST["mdp"]== null ? $resultUser["mdp"] : password_hash($_POST["mdp"], PASSWORD_DEFAULT);
    $localisation = $_POST["localisation"]== null ? resultUser["localisation"]: $_POST["localisation"];
    $latitude = $_POST['lat']== null ? $resultUser['latitude'] : $_POST['latitude'];
    $longitude = $_POST['lon']== null ? $resultUser['longitude'] : $_POST['longitude'];
    $photo = ($_FILES['img']['name'])== null ? $resultUser["photo"] : $_FILES['img']['name'];

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

    $querymodifyUser = $mysqli->prepare("UPDATE Users set pseudo = ?,mail = ?,mdp = ?,localisation = ?,latitude = ?,longitude = ?,photo = ? WHERE id = ? ");

    $querymodifyUser->bind_param( 'ssissddi',$pseudo, $mail, $mdphash, $localisation, $latitude, $longitude, $photo, $userid);

    $modifyUser = $querymodifyUser->execute();

    move_uploaded_file($_FILES['img']['tmp_name'], 'uploads/users/' . basename($_FILES['img']['name']));

}
}
 

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
            <h1><?php echo $resultUser['pseudo']?></h1>
       <ul>
        <?php
         echo "<li> pseudo :" . $resultUser['pseudo'] ."</li>";
         echo "<li> @mail:" . $resultUser['mail'] ."</li>";
         echo "<li> localisation:" . $resultUser['localisation'] ."</li>";
         echo "<li> date de création du compte:" . $resultUser['date'] ."</li>";
         ?>
        </ul>
        </section>
        <section id="UserPhoto">
        <?php
        echo "<img src='./uploads/users/".$resultUser['photo']."'/>"
        ?>
        </section>
        <section id="createForm">
            <h1>Modifier Profil de <?php echo $resultUser['pseudo']?></h1>
            <form action="" method="post" enctype="multipart/form-data">
                <div>
               
                </div>
                <div>
                    <input type="number" name="localisation" maxlength="5" placeholder="votre code postal" required />
                </div>
                <div>
                    <input type="password" name="mdp" placeholder="votre mot de passe">
                </div>
                <div>
                    <input type="file" name="img" placeholder="votre photo de profil">
                </div>
                <input type="submit" value="VALIDER" />
            </form>

            <?php include("./localisation.php") ?>
         

</body>

</html>