<?php 

// Connexion à la base de données.

$mysqli = new mysqli("localhost", "root", "root", "voisinous");
//verification
if ($mysqli->connect_errno) {
    echo("Échec de la connexion : " . $mysqli->connect_error);
    exit();
}
// Fonction qui récupère les commentaires des utilisateurs dans la base de données.
function setComments($mysqli) { 
    if(isset($_POST['commentSubmit']) && isset($_SESSION['pseudo'])) {
        $pseudo = $_SESSION["pseudo"];

        $content= $_POST["content"];
        $userid = $_SESSION['id']; // $userid = $_POST["userid"];
        $date = "CURRENT_TIMESTAMP";
        $groupeid = $_GET['id'];
        $principale = '1'; // $principale = $_POST["principale"]; 


        $queryInsertMessage = "INSERT INTO Posts (content, userid, date, groupeid, principale) "
                    . "VALUES ('"
                    . $content . "',"
                    . "'" . $userid . "',"
                    . "CURRENT_TIMESTAMP" . ", '"
                    . $groupeid . "',"
                    . "'" . $principale . "');";

        $createmessage = $mysqli->query($queryInsertMessage);
            if ($createmessage === TRUE) {
                echo "Message envoyé";
            } else {
                echo "Erreur lors de l'insertion : " . $mysqli->error;
            }

               $querySearchUser = "SELECT * "
                    . "FROM Users "
                    . "WHERE "
                    . "pseudo LIKE '" . $pseudo . "'"
                    ;

                    $searchUser = $mysqli->query($querySearchUser);
                    $result =$searchUser->fetch_assoc();
    }
}


// Fonction qui permet d'afficher les messages instantanées.
function getComments($mysqli) {
    // Sélectionne tous les commentaires de tous les utilisateurs
    $selectComments = "SELECT p.content, p.date, u.pseudo 
                       FROM Posts p
                       INNER JOIN Users u ON p.userid = u.id";
    $queryGetComments = $mysqli->query($selectComments);

    $allComments = array();
    foreach($queryGetComments as $comments){
        array_push($allComments,$comments);
    }
    $allComments = array_reverse($allComments);
        if ($queryGetComments) {
            foreach($allComments as $comments) {
            // Parcoure les résultats et les affiche 
            echo "<section id='groupFeed'>
                    <article class='message'>
                        <div class='messageHeader'>
                            <p>" . $comments['date'] . "</p> 
                            <p>" . $comments['pseudo'] . "</p>
                        </div>
                        <br><p>" . $comments["content"] . "</p>
                        <div class='messageFooter'>
                            <p>♥ 13</p>
                        </div>
                    </article>
                  </section>";  
        }
    } else {
        echo "Erreur lors de la récupération des commentaires : " . $mysqli->error;
    }
}

function getAllCommentsByUser($mysqli) {
    $querygetAllMessages = 
    "SELECT *, DATE_FORMAT(date, '%d-%m-%Y') AS formatted_date  FROM Posts
    WHERE userid = " . $_SESSION['id'] . ";";
    $queryAllMessages = $mysqli->query($querygetAllMessages);



    $allMessages = array();
    foreach($queryAllMessages as $message){
        array_push($allMessages,$message);
    }
    $allMessages = array_reverse($allMessages);

    foreach($allMessages as $message){

        $date = $message['formatted_date'];
        $heure = date('H:i', strtotime($message['date'])); 

        $queryGroupName = 
        "SELECT name FROM groupes
        WHERE id = " . $message['groupeid'] . ";";
        $getGroupName = $mysqli->query($queryGroupName);
        $groupe = $getGroupName->fetch_array();

        $queryUserPseudo = 
        "SELECT pseudo FROM users
        WHERE id = " . $message['userid'] . ";";
        $getUserPseudo = $mysqli->query($queryUserPseudo);
        $user = $getUserPseudo->fetch_array();

        echo "<article class='message'>
                    <div class='messageHeader'>
                        <p> le " . $date . " à " . $heure . "</p>
                        <p>par " . $user['pseudo'] . " (<a href='./groupPage.php?id=". $message['groupeid'] . "'>" . $groupe['name'] . "</a>)</p>
                    </div>
                    <p>" . $message['content'] . "</p>
                    <div class='messageFooter'>
                        <a href=''>♥ 256</a>
                    </div>
                </article>";
    }
}

function getAllCommentsByGroup($mysqli, $groupeid) {
    $querygetAllMessagesGroup = 
    "SELECT *, DATE_FORMAT(date, '%d-%m-%Y') AS formatted_date FROM Posts
    WHERE groupeid = " . $groupeid .";";
    $queryMessagesGroup = $mysqli->query($querygetAllMessagesGroup);

    $allMessagesGroup = array();
    foreach($queryMessagesGroup as $message){
        array_push($allMessagesGroup,$message);
    }
    $allMessagesGroup = array_reverse($allMessagesGroup);

    foreach($allMessagesGroup as $message){

        $date = $message['formatted_date'];
        $heure = date('H:i', strtotime($message['date'])); 

        $queryGroupName = 
        "SELECT name FROM groupes
        WHERE id = " . $message['groupeid'] . ";";
        $getGroupName = $mysqli->query($queryGroupName);
        $groupe = $getGroupName->fetch_array();

        $queryUserPseudo = 
        "SELECT pseudo FROM users
        WHERE id = " . $message['userid'] . ";";
        $getUserPseudo = $mysqli->query($queryUserPseudo);
        $user = $getUserPseudo->fetch_array();

        echo "<article class='message'>
                    <div class='messageHeader'>
                    <p> le " . $date . " à " . $heure . "</p>
                        <p>par " . $user['pseudo'] . "</p>
                    </div>
                    <p>" . $message['content'] . "</p>
                    <div class='messageFooter'>
                        <a href=''>♥ 256</a>
                    </div>
                </article>";
    }

    if (empty($allMessagesGroup)) {
        echo "<p class='empty'>Aucun message dans ce groupe</p>";
    }
}

function displayGroupMessages($mysqli) {
    $queryGetListGroupe = "SELECT *
    FROM groupemembers
    WHERE userid = " . $_SESSION['id'] . ";";
    $listGroupeID = $mysqli->query($queryGetListGroupe);

    $queryGetListPost ="SELECT content FROM `posts` 
    WHERE userid AND groupeid = 12;";
    $listPosts = $mysqli->query($queryGetListPost);

    // Si la requête est réussie.
    if ($listGroupeID) {
    // Affichage des messages par ordre décroissant.
    $allListGroups = array();
    foreach($listGroupeID as $listGroup){
        if($listPosts){
            array_push($allListGroups,$listGroup, $listPosts);
        }
    }
    $allListGroups = array_reverse($allListGroups);
// nouvelle query dans "posts" et boucle si le groupid = ce quil ya dans l'array alllistgroups avec une condition si groupid/userid a un content 

    foreach($allListGroups as $listGroup) {

        $queryGroupName = 
        "SELECT name FROM groupes
        WHERE id = " . $listGroup['groupid'] . ";";
        $getGroupName = $mysqli->query($queryGroupName);
        $groupe = $getGroupName->fetch_array();

        $queryUserPseudo = 
        "SELECT pseudo FROM users
        WHERE id = " . $listGroup['userid'] . ";";
        $getUserPseudo = $mysqli->query($queryUserPseudo);
        $user = $getUserPseudo->fetch_array();

        echo "<article class='message'>
                    <div class='messageHeader'>
                        <p> le " . $message['date'] . "</p>
                    <p>par " . $user['pseudo'] . " (<a href='./groupPage.php?id=". $listGroup['groupid'] . "'>" . $groupe['name'] . "</a>)</p>
                    </div>
                    <p>" . $message['content'] . "</p>
                    <div class='messageFooter'>
                        <a href=''>♥ 256</a>
                    </div>
                </article>";
     } 
}
}

?>