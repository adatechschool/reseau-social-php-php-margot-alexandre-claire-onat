<?php 
include('./sessionprolong.php');
include("./messageFonctions.php");

$mysqli = new mysqli("localhost", "root", "root", "voisinous");
    //verification
    if ($mysqli->connect_errno){
        echo("Échec de la connexion : " . $mysqli->connect_error);
        exit();
    }
    else{
        // $groupId = $_GET['id']; // recupéré id du groupe grace a l'url
        // $querySearchGroup = "SELECT * "
        // . "FROM groupes "
        // . "WHERE "
        // . "id = '" . $groupId . "'";
        // $searchGroup = $mysqli->query($querySearchGroup);
        // $resultGroup = $searchGroup->fetch_assoc();

        $groupId = $_GET['id']; // Récupération de l'ID du groupe depuis l'URL

        $querySearchGroup = "SELECT *, DATE_FORMAT(date, '%d-%m-%Y') AS formatted_date "
            . "FROM groupes "
            . "WHERE id = '" . $groupId . "'";

        $searchGroup = $mysqli->query($querySearchGroup);

        if ($searchGroup) {
            $resultGroup = $searchGroup->fetch_assoc();

            if ($resultGroup) {
                // Accédez à la date formatée
                $formattedDate = $resultGroup['formatted_date'];

            } else {
                echo "Aucun groupe trouvé avec cet ID.";
            }
        } else {
            echo "Erreur lors de l'exécution de la requête : " . $mysqli->error;
        }

        
        $queryGetUserIdForGroupe = "SELECT * "
        . "FROM groupemembers "
        . "WHERE " 
        . "groupid = '" . $groupId . "'";
        $searchUserForGroup = $mysqli->query($queryGetUserIdForGroupe);
        $resultUserForGroup = array();
            foreach ($searchUserForGroup as $userId){
                    //print_r($userId);
                    $queryUserInfo = "SELECT * 
                    FROM users
                    WHERE id = " . $userId['userid'] . ";";
                    $userInfo = $mysqli->query($queryUserInfo);
                    $resultUserOfGroup = $userInfo->fetch_array();
                    array_push($resultUserForGroup,$resultUserOfGroup);
           } 
           function isUserMember($mysqli){
            $queryMembership = "
            SELECT *
            FROM groupemembers
            WHERE userid = " . $_SESSION['id'] . "
            AND groupid = " . $_GET['id'] . ";";
            $membership = $mysqli->query($queryMembership);
            if($membership->num_rows>=1){
                return true;
            }
            return false;
        }
        }



        ?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($resultGroup['name']); ?></title>

    <link rel="icon" type="image/png" href="logo.png" />

    <link rel="stylesheet" href="./htmlcss/stylesheets/_body.css">
    <link rel="stylesheet" href="./htmlcss/stylesheets/groupPage.css">
    <link rel="stylesheet" href="./htmlcss/stylesheets/message.css">
</head>

<body>
    <?php include("htmlcss/navbar.php"); ?>

    <main>
    <section class="left" id="groupFeed">
            <?php 
        if(isUserMember($mysqli)==true) {
            setComments($mysqli);
            echo ("<form action='".getAllCommentsByGroup($mysqli, $groupId)."' method='POST'>
            <textarea name='content' style='color:grey;' placeholder='Ecrivez quelque chose ...'></textarea>
            <button class='greyBtn' id='newmessage' type='submit' value='envoyer' name='commentSubmit'>NOUVEAU MESSAGE</button>
            </form>");

            // getAllCommentsByGroup($mysqli, $groupId);
            

        }
        else {
            echo ("
            <a href='./joinGroupe.php?id=" . $groupId . "'><button class='redBtn' id='joingroup'>Rejoindre le groupe</button></a>
           ");
        }




        ?>
        </section>

        <aside class="right" id="groupProfile">
            <article id="groupHeader">
                <?php 
                    if ($resultGroup['photo'] != 0) {
                        echo "<img src='./uploads/users/" . $resultGroup['photo'] . "'/>"; 
                    }?>
                
                <div>
                    <div>
                        <h3> <?php echo $resultGroup['name']?> </h3><br />
                        <p><?php echo "créé le " . $formattedDate; ?></p><br />
                        <span><?php echo $resultGroup['localisation']?></span>
                    </div>
                </div>
                <p><?php echo $resultGroup['description']?></p>
                <h3>Membres</h3>
                <ul>
                <?php foreach ($resultUserForGroup as $user){
                 echo "<li>" . $user['pseudo'] ."</li>";}
                 ?>
                 </ul> 
                 
                
            </article>
        </aside>

    </main>

</body>

</html>