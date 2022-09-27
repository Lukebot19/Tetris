<!DOCTYPE html>
<html>
    <head>
        <title>Register</title>
        <link rel="stylesheet" href="styles.css">
        

    </head>
    <body>
        <ul class="navbar">
            <!-- Items on the left -->
            <li style = "float: left" class="navbar_left">
                <a name="home" href="/index.php">Home</a>
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

            <div class="form" style = "background-color:#c7c7c7; box-shadow: 5px 5px; text-align: center; possition: center">

                <form action="index.php" method="post">
                    <label for="fname">First name:</label><br>
                    <input type="text" id="fname" name="fname" required><br>
                    <label for="lname">Last name:</label><br>
                    <input type="text" id="lname" name="lname" required><br>
                    <label for="uname">Username:</label><br>
                    <input type="text" id="uname" name="uname" required><br>
                    <label for="pwd">Password:</label><br>
                    <input type="password" id="pwd" name="pwd" placeholder="Password" required><br>
                    <label for="Cpwd">Confirm password:</label><br>
                    <input type="password" id="Cpwd" name="Cpwd" placeholder="Confirm password" required><br>
                    <label for="Cpwd">Display Scores on Leaderboard:</label><br>
                    <input type="radio" id="DSOL" name="display" value="1" required>
                    <label for="DSOL">Yes</label>
                    <input type="radio" id="DDSOL" name="display" value="0" required>
                    <label for="DDSOL">No</label><br>
                    <input type="submit" value="Submit">
                    
                </form>
                <script>
            var password = document.getElementById("pwd"), Cpwd = document.getElementById("Cpwd");

            function validatePassword(){
            if(password.value != Cpwd.value) {
                Cpwd.setCustomValidity("Passwords Don't Match");
            } else {
                Cpwd.setCustomValidity('');
            }
            }

            password.onchange = validatePassword;
            Cpwd.onkeyup = validatePassword;
        </script>
            </div>
        </div>
    </body>
</html>