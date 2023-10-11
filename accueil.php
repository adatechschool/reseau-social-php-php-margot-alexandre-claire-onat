<?php 
include("./sessionprolong.php"); 
include("./messageFonctions.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voisinous</title>

    <link rel="icon" type="image/png" href="logo.png" />

    <link rel="stylesheet" href="./htmlcss/stylesheets/_body.css">

</head>

<body>
    <?php include("./htmlcss/navbar.php") ?> 

    <main>
        <section class="left" id="feed">
            <h1>Derniers messages</h1>
            <?php getAllCommentsByUser($mysqli); ?>
            <?php displayGroupMessages($mysqli); ?>
        </section>
        <aside class="right" id="myGroups">
            <h1>Mes groupes</h1>
            <a href="newGroup.php"><button class="greyBtn" id="newgroup">Créer un groupe</button></a>
            <ul>
                <?php include("./displayGroups.php") ?>
            </ul>
        </aside>
    </main>


</body>

</html>