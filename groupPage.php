<?php 
$mysqli = new mysqli("localhost", "root", "root", "voisinous");
    //verification
    if ($mysqli->connect_errno){
        echo("Échec de la connexion : " . $mysqli->connect_error);
        exit();
    }
    else{
        $groupId = "2"; // recupéré id du groupe grace a l'url
        $querySearchGroup = "SELECT * "
        . "FROM groupes "
        . "WHERE "
        . "id = '" . $groupId . "'";
        $searchGroup = $mysqli->query($querySearchGroup);
        $result = $searchGroup->fetch_assoc();
        if($result != null){
            print_r($result);
        }
        else{
            $errorMessage = 'Aucun groupe a cette localisation !';
        }
        }
        
        ?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voisinous</title>

    <link rel="stylesheet" href="./htmlcss/stylesheets/_body.css">

</head>

<body>
    <?php include("htmlcss/navbar.php") ?>

    <main>
        <aside id="groupProfile">
            <article id="groupHeader">
                <img
                    src="https://scontent-cdg4-3.xx.fbcdn.net/v/t39.30808-6/295177885_469072138554182_3136524954159481461_n.png?_nc_cat=106&ccb=1-7&_nc_sid=a2f6c7&_nc_ohc=lr7_QKzipPwAX-JIw3E&_nc_ht=scontent-cdg4-3.xx&cb_e2o_trans=t&oh=00_AfBCbK-3QSZDGUaUXKm9ni9KPU_qhcC1CyCl6vMPKTh9Qw&oe=6522828A" />
                <div>
                    <div>
                        <h3> <?php echo $result['name']?> </h3><br />
                        <span><?php echo $result['localisation']?></span>
                    </div>
                </div>
                <p><?php echo $result['decription']?></p>
                <h3>Membres</h3>
                <ul>
                    <li>Gérard</li>
                    <li>Michel</li>
                    <li>Jacqueline</li>
                </ul>
            </article>
        </aside>
        <section id="groupFeed">
            <button id="newmessage">Nouveau message</button>
            <article class="message">
                <div class="messageHeader">
                    <p>5 octobre 2023</p>
                    <p>par Claire</p>
                </div>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi dignissim dignissim est ut elementum. Sed lacinia purus in mi tempor posuere. Proin eget lacinia turpis, a vulputate enim. Curabitur semper suscipit diam, et tincidunt ligula vestibulum sed. Curabitur ac ligula at libero scelerisque tristique id non odio. Phasellus vel ante quam. Sed semper eu orci laoreet interdum. Nullam vel est id lectus dignissim luctus congue eget tellus. Sed suscipit leo ut efficitur dictum. Maecenas et rutrum sapien. Etiam mollis venenatis odio, ut lobortis dui commodo ac. Maecenas et venenatis orci, eget viverra risus. Integer viverra hendrerit augue at accumsan.</p>
                <div class="messageFooter">
                    <p>♥ 13</p>
                </div>
            </article>
        </section>
    </main>


</body>

</html>