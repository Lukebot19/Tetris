<?php
$servername = "localhost";
$username = "ecm1417";
$password = "Password";
$schema = 'tetris';
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Leaderboard</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <ul class="navbar">
            <!-- Items on the left -->
            <li style = "float: left">
                <a name="home" href="/index.php">Home</a>
            </li>

            <!-- Items on the right -->
            <li>
                <a name="leaderboard" class="active" href="/leaderboard.php">Leaderboard</a>
            </li>
            <li>
                <a name="tetris" href="/tetris.php">Play Tetris</a>
            </li>
        </ul>

        <div class="main">
            <div class="form">
                <p> Leaderboard </p>
                <Table>


                    <?php $conn = new mysqli($servername, $username, $password, $schema);

                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }
                        $sql = "SELECT Scores.UserName, Score FROM Scores, Users WHERE Users.UserName = Scores.UserName AND Users.Display = '1'";    
                        $result = $conn->query($sql);
                        echo "<tr  style='background:blue;color:white;'><th>Username</th><th>Score</th></tr>";
                        
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr><th>" . $row['UserName'] . '</th>';
                            echo "<th>" . $row['Score'] . "</th>";
                            echo "</tr>";
                        }
                        

                    ?>
                </Table>
                
            </div>
            
        </div>
            
    </body>
</html>