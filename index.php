<?php  
$servername = "localhost";
$username = "ecm1417";
$password = "Password";
$schema = 'tetris';
$found = false;
session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Home</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <ul class="navbar">
            <!-- Items on the left -->
            <li style = "float: left">
                <a name="home" class="active" href="/index.php">Home</a>
            </li>

            <!-- Items on the right -->
            <li>
                <a name="leaderboard" href="/leaderboard.php">Leaderboard</a>
            </li>
            <li>
                <a name="tetris" href="/tetris.php">Play Tetris</a>
            </li>
        </ul>
        <div class="main">
            <div class="form">
                <?php 
                    if (isset($_POST['logout'])) {
                        session_destroy();
                        setcookie("score", "", time()-3600);
                        setcookie("done", "", time()-3600);
                        session_start();
                    }
                    if (isset($_COOKIE['score'])) {
                        $conn = new mysqli($servername, $username, $password, $schema);

                       
                        $sql = "INSERT INTO Scores (UserName, Score) VALUES ('".$_SESSION['uname']."', ".$_COOKIE['score'].");";
                        if ($conn->query($sql) === TRUE) {
                            echo "Score Posted Successfully";
                        } else {
                            echo "Error: " . $sql . "<br>" . $conn->error;
                        }
                        setcookie("score", "", time()-3600);
                        setcookie("done", "True");
                        header('Location: tetris.php');
                        
                    }
                    
                ?>
                <?php 
                echo "<p> Hello </p>";
                if (isset($_POST['Cpwd'])) {
                    
                    $conn = new mysqli($servername, $username, $password, $schema);

                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                        }

                    $sql = "SELECT UserName FROM Users";    
                    $result = $conn->query($sql);
                    
                    
                    while ($row = $result->fetch_array()) {
                        if ($row['UserName'] == $_POST['uname']) {
                            echo "<p> Username is already taken </p>";
                            $found = true;
                            break;
                        }
                    } 
                    if ($found == false) {
                        $sql = "INSERT INTO Users VALUES ('".$_POST['uname']."','".$_POST['fname']."','".$_POST['lname']."','".$_POST['pwd']."',".$_POST['display'].")";    
                        
                        if ($conn->query($sql) === TRUE) {
                            echo "New record created successfully";
                            $_SESSION['uname'] = $_POST['uname'];
                        } else {
                            echo "Error: " . $sql . "<br>" . $conn->error;
                        }

                        $conn->close();
                }

                }
                else if (isset($_POST['uname'])) {

                    // Create connection
                    $conn = new mysqli($servername, $username, $password, $schema);

                    // Check connection
                    if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                    }
                    $sql = "SELECT UserName, Password FROM Users WHERE UserName = '".$_POST['uname']."'";    
                    $result = $conn->query($sql);
                    $conn->close();
                    

                    
                    if ($result->num_rows > 0){
                        while ($row = $result->fetch_array()) {
                            if ($row['Password'] == $_POST['pwd']) {
                                $_SESSION['uname'] = $_POST['uname'];
                            } else {
                                echo "<p> Login failed </p>";
                            }
                        } 
                    } else {
                        echo "<p> Login failed </p>";
                    }
                      

                    
                }
                
                if (isset($_SESSION['uname'])) { 
                    echo "<p> Welcome to Tetris </p>";
                    echo "<form action='tetris.php'><input type='submit' value='Click here to play'></form>";
                    echo "<form action='index.php' method='post'><input name='logout' type='submit' value='Logout'></form>";
                } else { 
                    echo "<p> Not logged in </p> 
                    <form action='index.php' method='post'>
                        <label for='uname'>Username:</label><br>
                        <input type='text' id='uname' name='uname' placeholder='username' required><br>
                        <label for='pwd'>Password:</label><br>
                        <input type='password' id='pwd' name='pwd' placeholder='Password' required><br><br>
                        <input type='submit' value='Login'>
                        <p>Don't have a user account? <br> <a href='register.php'> Register now</a></p>
                    </form>";
                 } ?>

                 


                
                
            </div>
        </div>
            
        
    </body>
</html>