<?php
    $ACCESS_TOKEN="test";
?>

<?php
    if (isset($ACCESS_TOKEN))
    {
        $QUEUE=$_GET['queue'];
        switch ($QUEUE)
        {
            default: // Ask for Access Token
                ?>
                    <head>
                        <title>Step 1: Access Token | SoulGames Website Installer</title>
                        <link rel="stylesheet" type="text/css" href="https://soulgames.de/src/css/bootstrap/bootstrap.css">
                    </head>
                    <body>
                        <div class="container mx-auto"><br>
                            <form action="?queue=1" method="post">
                                <center>
                                    <h2>Access Token</h2>
                                    <p><a>Please enter your access token.</a> <a href="javascript:;">Where is my Access Token?</a></p><br>
                                </center>
                                <input class="form-control" type="text" name="accesstoken" placeholder="Access Token" required><br>
                                <button class="btn btn-primary" type="submit" name="submit">Apply</button>
                            </form>
                        </div>
                    </body>
                <?php
                break;
            case 1:
                $ACCESS_TOKEN_ENTER=$_POST["accesstoken"];
                if($ACCESS_TOKEN == $ACCESS_TOKEN_ENTER)
                {
                    ?>
                        <head>
                            <title>Step 2: Database | SoulGames Website Installer</title>
                            <link rel="stylesheet" type="text/css" href="https://soulgames.de/src/css/bootstrap/bootstrap.css">
                        </head>
                        <body>
                            <div class="container mx-auto"><br>
                                <form action="?queue=1" method="post">
                                    <center>
                                        <h2>Database</h2>
                                        <p><a>Please enter your MySQL database credentials.</a></p><br>
                                    </center>
                                    <input class="form-control" type="text" name="host" placeholder="Server" required><br>
                                    <input class="form-control" type="text" name="user" placeholder="Username" required><br>
                                    <input class="form-control" type="text" name="pass" placeholder="Password" required><br>
                                    <input class="form-control" type="text" name="name" placeholder="Database name" required><br>
                                    <button class="btn btn-primary" type="submit" name="submit">Apply</button>
                                </form>
                            </div>
                        </body>
                    <?php
                }
                else{
                    header('Location: ?queue=0');
                    exit;
                }
                break;
            case 2:
                $host=$_POST['host'];
                $user=$_POST['user'];
                $pass=$_POST['pass'];
                $name=$_POST['name'];

                try
                {
                    $mysql = new PDO("mysql:host=$host;dbname=$name", $user, $pass);

                    $changelogStmt = $mysql->prepare("
						CREATE TABLE `changelog` (
							`ID` INT(11) NOT NULL AUTO_INCREMENT,
							`TITLE` VARCHAR(255) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
							`CHANGENEWS` VARCHAR(5000) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
							`CREATED_AT` DATETIME NULL DEFAULT NULL,
							`CAT` VARCHAR(255) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
							UNIQUE INDEX `ID` (`ID`) USING BTREE
						)
						COLLATE='latin1_swedish_ci'
						ENGINE=InnoDB
						;
					");
                    $changelogStmt->execute();


                    $accountsStmt = $mysql->prepare("
						CREATE TABLE `accounts` (
							`USERNAME` VARCHAR(50) NOT NULL COLLATE 'utf8mb4_general_ci',
							`PASSWORD` VARCHAR(200) NOT NULL COLLATE 'utf8mb4_general_ci',
							UNIQUE INDEX `USERNAME` (`USERNAME`) USING BTREE
						)
						COLLATE='utf8mb4_general_ci'
						ENGINE=InnoDB
						;
					");
                    $accountsStmt->execute();
					
					
					$betakeysStmt = $mysql->prepare("
						CREATE TABLE `betakeys` (
							`ID` INT(11) NOT NULL AUTO_INCREMENT,
							`BETAPW` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
							UNIQUE INDEX `ID` (`ID`) USING BTREE,
							UNIQUE INDEX `BETAPW` (`BETAPW`) USING BTREE
						)
						COLLATE='utf8mb4_general_ci'
						ENGINE=InnoDB
						;
					");
                    $betakeysStmt->execute();
                    

					$betausersStmt = $mysql->prepare("
						CREATE TABLE `betausers` (
							`ID` INT(11) NOT NULL AUTO_INCREMENT,
							`USERNAME` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
							`isB` TINYINT(1) NULL DEFAULT NULL,
							UNIQUE INDEX `ID` (`ID`) USING BTREE,
							UNIQUE INDEX `USERNAME` (`USERNAME`) USING BTREE
						)
						COLLATE='utf8mb4_general_ci'
						ENGINE=InnoDB
						;
					");
                    $betausersStmt->execute();
                }
                catch (PDOException $e)
                {
                    session_start();
                    $_SESSION["sqlerror"] = $e->getMessage();

                    header('Location: ?queue=1');
                    exit;
                }

                // TODO: SET IN SESSION THE MYSQL DATA

                // TODO: ASK FOR ADMIN USER
                break;
            case 3:
                // TODO: SET ADMIN USER FROM SESSION MYSQL DATA

                // TODO: REDIRECT TO CASE 4
                break;
            case 4:
                // TODO: DOWNLOAD
                // TODO: EXRRACT
                // TODO: (REMOVE/)SET MYSQL.PHP WITH SESSION MYSQL DATA

                // TODO: REDIRECT TO WEBSITE
        }
    }
?>
