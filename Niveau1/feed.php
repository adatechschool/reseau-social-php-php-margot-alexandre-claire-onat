<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Flux</title>         
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
    <?php include ("./header.php"); ?>

        <div id="wrapper">
            <?php
            
            $userId = intval($_GET['user_id']);
            ?>
            <?php
            /**
             * Etape 2: se connecter à la base de donnée
             */
            $mysqli = new mysqli("localhost", "root", "root", "socialnetwork");
            ?>

            <aside>
                <?php
                /**
                 * Etape 3: récupérer le nom de l'utilisateur
                 */
                $laQuestionEnSql = "SELECT * FROM `users` WHERE id= '$userId' ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                $user = $lesInformations->fetch_assoc();
                //@todo: afficher le résultat de la ligne ci dessous, remplacer XXX par l'alias et effacer la ligne ci-dessous
                echo "<pre>" . print_r($user, 1) . "</pre>";
                ?>
                <img src="user.jpg" alt="Portrait de l'utilisatrice"/>
                <section>
                    <h3>Présentation</h3>
                    <p>Sur cette page vous trouverez tous les message des utilisatrices
                        auxquel est abonnée l'utilisatrice XXX
                        (n° <?php echo $userId ?>)
                    </p>

                </section>
            </aside>
            <main>
                
                /**
                 * Etape 3: récupérer tous les messages des abonnements
                 */
                <?php
            
                $laQuestionEnSql = "SELECT * FROM `Posts`";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                // Vérification
                if ( ! $lesInformations)
                {
                    echo("Échec de la requete : " . $mysqli->error);
                    exit();
                } 
?>
<?php
                $laQuestionEnSql = "
                    SELECT posts.content,
                    posts.created,
                    users.alias as author_name,  
                    count(likes.id) as like_number,  
                    GROUP_CONCAT(DISTINCT tags.label) AS taglist 
                    FROM followers 
                    JOIN users ON users.id=followers.followed_user_id
                    JOIN posts ON posts.user_id=users.id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    WHERE followers.following_user_id='$userId' 
                    GROUP BY posts.id
                    ORDER BY posts.created DESC  
                    ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                if ( ! $lesInformations)
                {
                    echo("Échec de la requete : " . $mysqli->error);
                }

            
                ?>                
                <article>
                    <h3>
                        <time datetime='2020-02-01 11:12:13' >31 février 2010 à 11h12</time>
                    </h3>
                    <address>par AreTirer</address>
                    <div>
                        <p>Ceci est un paragraphe</p>
                        <p>Ceci est un autre paragraphe</p>
                        <p>... de toutes manières il faut supprimer cet 
                            article et le remplacer par des informations en 
                            provenance de la base de donnée</p>
                    </div>                                            
                    <footer>
                        <small>♥ 132</small>
                        <a href="">#lorem</a>,
                        <a href="">#piscitur</a>,
                    </footer>
                </article>
                <?php
                // et de pas oublier de fermer ici vote while
                ?>


            </main>
        </div>
    </body>
</html>
