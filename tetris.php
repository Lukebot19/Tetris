<?php
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Play Tetris</title>
        <link rel="stylesheet" href="styles.css">
        <style>           
            
            .empty {
                width: 29.5px;
                height: 26.2px;
                display: inline-block;
                outline: 2px solid black;
                visibility: hidden;
            }

            .block {
                width: 29.5px;
                height: 26.2px;
                background-color: blue;
                outline: 3px solid black;
                display: inline-block;
            }

            #L {
                background-color: #0008ff;
            }
            #Z {
                background-color: #02f72b;
            }
            #S {
                background-color: #ff0022;
            }
            #T {
                background-color: #6e0066;
            }
            #O {
                background-color: #ffe100;
            }
            #I {
                background-color: #07e6d3;
            }
        </style>
        
    </head>
    <body>
        
        <ul class="navbar">
            <!-- Items on the left -->
            <li style = "float: left">
                <a name="home" href="/index.php">Home</a>
            </li>

            <!-- Items on the right -->
            <li>
                <a name="leaderboard" href="/leaderboard.php">Leaderboard</a>
            </li>
            <li>
                <a name="tetris" class="active" href="/tetris.php">Play Tetris</a>
            </li>
        </ul>

        <?php 
            if (isset($_SESSION['uname'])) {
                
            } else {
                header('Location: index.php');
            }
        ?>

        <div class="main">
            <div class="form">
                <button id="playButton" style='position: absolute; top:50%; transform: translateY(-50%); transform: translateX(-50%);' type="button">Play Game</button>
                <div class="form" id="endScreen" style='position: absolute; top:50%; transform: translateY(-50%); transform: translateX(-50%); Display: none; blackground-color: #c7c7c7;'><p>Game Over</p></div>
                <div id= 'score'></div>
                <div id='tetris-bg'></div>
                    
                        <script>
                            var score = 1;
                            var firstTime = true;
                            var running = true;
                            var rotation = 0;

                            var currentBlock = [[4,0],
                                                [5,0],
                                                [4,1],
                                                [5,1]];
                            var audio = new Audio('/Tetris_theme.ogg.mp3');


                            var grid = [["","","","","O","O","","","",""],
                                        ["","","","","O","O","","","",""],
                                        ["","","","","","","","","",""],
                                        ["","","","","","","","","",""],
                                        ["","","","","","","","","",""],
                                        ["","","","","","","","","",""],
                                        ["","","","","","","","","",""],
                                        ["","","","","","","","","",""],
                                        ["","","","","","","","","",""],
                                        ["","","","","","","","","",""],
                                        ["","","","","","","","","",""],
                                        ["","","","","","","","","",""],
                                        ["","","","","","","","","",""],
                                        ["","","","","","","","","",""],
                                        ["","","","","","","","","",""],
                                        ["","","","","","","","","",""],
                                        ["","","","","","","","","",""],
                                        ["","","","","","","","","",""],
                                        ["","","","","","","","","",""],
                                        ["","","","","","","","","",""]];

                            var gamePieces = {
                                'L' : [ [ [2,1],[2,2],[2,3],[3,3] ], [ [1,2], [2,2], [3,2], [3,1] ], [ [2,3], [2,2], [2,1], [1,1] ], [ [3,2], [2,2], [1,2], [1,3] ] ],
                                'Z' : [ [ [1,1],[2,1],[2,2],[3,2] ], [ [1,3], [1,2], [2,2], [2,1] ], [ [3,3], [2,3], [2,2], [1,2] ], [ [3,1], [3,2], [2,2], [2,3] ] ],
                                'S' : [ [ [1,2],[2,2],[2,1],[3,1] ], [ [2,3], [2,2], [1,2], [1,1] ], [ [3,2], [2,2], [2,3], [1,3] ], [ [2,1], [2,2], [3,2], [3,3] ] ],
                                'T' : [ [ [1,2],[2,2],[2,3],[3,2] ], [ [2,3], [2,2], [3,2], [2,1] ], [ [1,2], [2,2], [2,1], [3,2] ], [ [2,1], [2,2], [1,2], [2,3] ] ],
                                'O' : [ [ [1,1],[1,2],[2,1],[2,2] ], [ [1,1], [1,2], [2,1], [2,2] ], [ [1,1], [1,2], [2,1], [2,2] ], [ [1,1], [1,2], [2,1], [2,2] ] ],
                                'I' : [ [ [3,1],[3,2],[3,3],[3,4] ], [ [1,2], [2,2], [3,2], [4,2] ], [ [2,1], [2,2], [2,3], [2,4] ], [ [1,3], [2,3], [3,3], [4,3] ] ]};
                            


                            function drawGrid(){
                                if (firstTime === true) {
                                    document.getElementById('score').innerHTML = "Score:" + score;
                                    firstTime = false;
                                }
                                document.getElementById('tetris-bg').innerHTML = "";
                                for (var y=0; y<grid.length; y++){
                                    for (var x=0; x<grid[y].length; x++){
                                        if (grid[y][x] === "") {
                                            document.getElementById('tetris-bg').innerHTML += "<div class='empty'></div>";
                                        } else if (grid[y][x] === "L" || grid[y][x] === "LFinal") {
                                            document.getElementById('tetris-bg').innerHTML += "<div id='L' class='block'></div>";
                                        } else if (grid[y][x] === "Z" || grid[y][x] === "ZFinal") {
                                            document.getElementById('tetris-bg').innerHTML += "<div id='Z' class='block'></div>";
                                        } else if (grid[y][x] === "S" || grid[y][x] === "SFinal") {
                                            document.getElementById('tetris-bg').innerHTML += "<div id='S' class='block'></div>";
                                        } else if (grid[y][x] === "T" || grid[y][x] === "TFinal") {
                                            document.getElementById('tetris-bg').innerHTML += "<div id='T' class='block'></div>";
                                        } else if (grid[y][x] === "O" || grid[y][x] === "OFinal") {
                                            document.getElementById('tetris-bg').innerHTML += "<div id='O' class='block'></div>";
                                        } else if (grid[y][x] === "I" || grid[y][x] === "IFinal") {
                                            document.getElementById('tetris-bg').innerHTML += "<div id='I' class='block'></div>";
                                        }
                                    }
                                    document.getElementById('tetris-bg').innerHTML += "<br>";
                                }
                            }

                            function moveShapesDown() {
                                var canMove = true;
                                for (var y = currentBlock.length-1; y>=0; y--) {
                                    for (var x = 0; x<currentBlock[y].length; x++) {
                                        if (currentBlock[y][1]+1 === grid.length || grid[currentBlock[y][1]+1][currentBlock[y][0]].includes("Final")) {
                                            canMove = false;
                                            freeze();
                                        }
                                    }
                                }
                               
                                if (canMove) {
                                    letter = grid[currentBlock[1][1]][currentBlock[1][0]];
                                    for (var y = currentBlock.length-1; y>=0; y--) {
                                        grid[currentBlock[y][1]][currentBlock[y][0]] = "";
                                    }
                                    for (var y = currentBlock.length-1; y>=0; y--) {
                                       grid[currentBlock[y][1]+1][currentBlock[y][0]] = letter;
                                       currentBlock[y][1] += 1;    
                                    }
                                    
                                    drawGrid();
                                }         
                                checkLines();                         
                            }

                            function moveShapesLeft() {
                                var canMove = true;
                                for (var y = currentBlock.length-1; y>=0; y--) {
                                    for (var x = 0; x<currentBlock[y].length; x++) {
                                        try {
                                            if (currentBlock[y][x] === 0 || grid[currentBlock[y][1]][currentBlock[y][0]-1].includes("Final")) {
                                            canMove = false;
                                            
                                            }
                                        } catch {
                                            if (currentBlock[y][x] === 0 || grid[y][x].includes("Final")) {
                                            canMove = false;
                                           
                                            }
                                        }
                                        
                                    }
                                }
                                if (canMove) {
                                    letter = grid[currentBlock[1][1]][currentBlock[1][0]];
                                    for (var y = currentBlock.length-1; y>=0; y--) {
                                        grid[currentBlock[y][1]][currentBlock[y][0]] = "";
                                    }
                                    for (var y = currentBlock.length-1; y>=0; y--) {
                                       grid[currentBlock[y][1]][currentBlock[y][0]-1] = letter;
                                       currentBlock[y][0] -= 1;    
                                    }
                                    drawGrid();
                                }         
                                checkLines();                                     
                            }

                            function moveShapesRight() {
                                var canMove = true;
                                for (var y = currentBlock.length-1; y>=0; y--) {
                                    for (var x = 0; x<currentBlock[y].length; x++) {
                                        try {
                                            if (currentBlock[y][x] === 9 || grid[currentBlock[y][1]][currentBlock[y][0]+1].includes("Final")) {
                                            canMove = false;
                                         
                                            }
                                        } catch {
                                            if (currentBlock[y][x] === 9 || grid[y][x].includes("Final")) {
                                            canMove = false;
                                          
                                            }
                                        }
                                        
                                    }
                                }
                                
                                if (canMove) {
                                    letter = grid[currentBlock[1][1]][currentBlock[1][0]];
                                    for (var y = currentBlock.length-1; y>=0; y--) {
                                        grid[currentBlock[y][1]][currentBlock[y][0]] = "";
                                    }
                                    for (var y = currentBlock.length-1; y>=0; y--) {
                                       grid[currentBlock[y][1]][currentBlock[y][0]+1] = letter;
                                       currentBlock[y][0] += 1;    
                                    }
                                    drawGrid();
                                }         
                                checkLines();
                            }



                            function freeze() {
                                for (var y=grid.length-1; y>=0; y--){
                                    for (var x=0; x<grid[y].length; x++){
                                        if (grid[y][x].length === 1) {
                                            grid[y][x] = grid[y][x] + "Final";
                                        }
                                    }
                                }
                                checkLines();
                                var num = Math.floor(Math.random()*6);
                                switch (num){
                                    case 0:
                                        if (grid[0][4] === "" && grid[1][4] === "" && grid[2][4] === "" && grid[2][5] === "") {
                                        grid[0][4] = "L";
                                        grid[1][4] = "L";
                                        grid[2][4] = "L";
                                        grid[2][5] = "L";
                                        currentBlock = [[4,0],[4,1],[4,2],[5,2]];
                                        rotation=0;
                                        break;
                                        } else {
                                            gameEnd();
                                            break;
                                        }
                                    case 1:
                                        if (grid[0][4] === "" && grid[0][5] === "" && grid[1][5] === "" && grid[1][6] === "") {
                                        grid[0][4] = "Z";
                                        grid[0][5] = "Z";
                                        grid[1][5] = "Z";
                                        grid[1][6] ="Z";
                                        currentBlock = [[4,0],[5,0],[5,1],[6,1]];
                                        rotation=0;
                                        break;
                                        } else {
                                            gameEnd();
                                            break;
                                        }
                                    case 2:
                                        if (grid[0][5] === "" && grid[0][6] === "" && grid[1][4] === "" && grid[1][5] === "") {
                                        grid[0][5] = "S";
                                        grid[0][6] = "S";
                                        grid[1][4] = "S";
                                        grid[1][5] = "S";
                                        currentBlock = [[4,1],[5,1],[5,0],[6,0]];
                                        rotation=0;
                                        break;
                                        } else {
                                            gameEnd();
                                            break;
                                        }
                                    case 3:
                                        if (grid[0][4] === "" && grid[0][5] === "" && grid[0][6] === "" && grid[1][5] === "") {
                                        grid[0][4] = "T";
                                        grid[0][5] = "T";
                                        grid[0][6] = "T";
                                        grid[1][5] = "T";
                                        currentBlock = [[4,0],[5,0],[5,1],[6,0]];
                                        rotation=0;
                                        break;
                                        } else {
                                            gameEnd();
                                            break;
                                        }
                                    case 4:
                                        if (grid[0][4] === "" && grid[0][5] === "" && grid[1][4] === "" && grid[1][5] === "") {
                                        grid[0][4] = "O";
                                        grid[0][5] = "O";
                                        grid[1][4] = "O";
                                        grid[1][5] = "O";
                                        currentBlock = [[4,0],[5,0],[4,1],[5,1]];
                                        rotation=0;
                                        break;
                                        } else {
                                            gameEnd();
                                            break;
                                        }
                                    case 5:
                                        if (grid[0][4] === "" && grid[1][4] === "" && grid[2][4] === "" && grid[3][4] === "") {
                                        grid[0][4] = "I";
                                        grid[1][4] = "I";
                                        grid[2][4] = "I";
                                        grid[3][4] = "I";
                                        currentBlock = [[4,0],[4,1],[4,2],[4,3]];
                                        rotation=0;
                                        break;
                                        } else {
                                            gameEnd();
                                            break;
                                        }
                                    default:
                                        break;
                                }
                                score += 1;
                                if (running) {
                                    document.getElementById('score').innerHTML = "Score:" + score;
                                }
                            }

                            function gameEnd() {
                                if (running) {
                                    running = false;
                                    element = document.getElementById('endScreen');
                                    element.innerHTML += "<div><p>" + score + "</p></div><button id='scoreButton' type='button'>Submit Score</button>";
                                    element.style.display = 'inline';
                                    audio.pause();
                                    document.cookie = "score="+ score + "";
                                    scoreSubmit = document.getElementById("scoreButton");
                                    scoreSubmit.addEventListener("click", submitScore);
                                }
                                
                            }

                            function checkLines() {
                                for (var y=grid.length-1; y>=0; y--) {
                                    fullLine = true;
                                    for (var x=0; x<grid[y].length; x++) {
                                        if (grid[y][x].indexOf("Final") === -1){
                                            fullLine = false;
                                        }
                                    }
                                    if (fullLine) {
                                        grid.splice(y,1);
                                        grid.splice(0, 0, ["","","","","","","","","",""]);
                                        y--;
                                    }
                                }
                            }

                            function rotate() {
                                
                                var letter = grid[currentBlock[1][1]][currentBlock[1][0]];
                                
                                if (rotation === 3) {
                                    var newRotation = 0;
                                } else {
                                    var newRotation = rotation + 1;
                                }
                                tempBlock = gamePieces[letter][newRotation];
                                tempCurrentBlock = [ [0,0],[0,0],[0,0],[0,0] ]
                                for (var y = 0; y < currentBlock.length; y++) {
                                     for (var x = 0; x<2; x++) {
                                        grid[currentBlock[y][1]][currentBlock[y][0]] = "";
                                        tempCurrentBlock[y][x] = currentBlock[y][x];
                                    }
                                }

                        
                                var canMove = true;
                                for (var y = 0; y < tempBlock.length; y++) {
                                    for (var x = 0; x<2; x++) {
                                        tempCurrentBlock[y][x] += (gamePieces[letter][newRotation][y][x] - gamePieces[letter][rotation][y][x]);
                                        try {
                                        if (tempCurrentBlock[y][1] === 9 || grid[tempCurrentBlock[y][1]][tempCurrentBlock[y][0]+1].includes("Final")) {
                                            canMove = false;
                                        } else if (tempCurrentBlock[y][x] === 0 || grid[tempCurrentBlock[y][1]][tempCurrentBlock[y][0]-1].includes("Final")) {
                                            canMove = false;  
                                        }
                                    } catch {
                                        if (tempCurrentBlock[y][x] === 9 || grid[y][x].includes("Final")) {
                                            canMove = false;
                                        } else if (tempCurrentBlock[y][x] === 0 || grid[y][x].includes("Final")) {
                                            canMove = false;
                                        }
                                    }
                                    }
                                }
                                if (!canMove) {
                                    for (var y = 0; y < currentBlock.length; y++) {
                                    
                                        grid[currentBlock[y][1]][currentBlock[y][0]] = letter;
                                        
                                    }
                                }
                                
                                if (canMove) {
                                    rotation = newRotation;
                                    currentBlock = tempCurrentBlock;
                                    for (var y = 0; y<=currentBlock.length-1; y++) {
                                        
                                        grid[currentBlock[y][1]][currentBlock[y][0]] = letter;

                                    }
                                    drawGrid();
                                }         
                                checkLines(); 
                            }

                            document.onkeydown = function(e) {
                                if (running) {
                                    switch (e.keyCode) {
                                        case 37:
                                            moveShapesLeft();
                                            break;
                                        case 39:
                                            moveShapesRight();
                                            break;
                                        case 40:
                                            moveShapesDown();
                                            break;
                                        case 38:
                                            rotate();
                                        default:
                                            break;
                                    }
                                }
                            }

                            function gameLoop() {
                                if (running) {
                                    moveShapesDown();
                                    drawGrid();
                                    setTimeout(gameLoop, 1000);
                                }
                            }
                            
                            
                            function startNewGame() {
                                console.log("Starting new game");
                                audio.play();
                                audio.loop = true;
                                score = 1;
                                element = document.getElementById("playButton");
                                element.style.display = 'none';
                                again = document.getElementById('endScreen');
                                again.style.display = 'none';
                                drawGrid();
                                gameLoop();
                            }

                            var x = getCookie('done');
                            
                            if (x === 'True') {
                                element = document.getElementById('endScreen');
                                element.innerHTML += "<div><p>Score Saved!</p></div><button id='againButton' type='button'>Play Again</button>";
                                element.style.display = 'inline';
                                again = document.getElementById('againButton');
                                again.addEventListener("click", startNewGame);
                            }
                            element = document.getElementById("playButton");
                            scoreSubmit = document.getElementById("scoreButton");
                            element.addEventListener("click", startNewGame);
                            scoreSubmit.addEventListener("click", submitScore);
                            
                            function submitScore() {
                                window.location.replace("index.php");
                               
                            }

                            function getCookie(cname) {
                                let name = cname + "=";
                                let decodedCookie = decodeURIComponent(document.cookie);
                                let ca = decodedCookie.split(';');
                                for(let i = 0; i <ca.length; i++) {
                                    let c = ca[i];
                                    while (c.charAt(0) == ' ') {
                                    c = c.substring(1);
                                    }
                                    if (c.indexOf(name) == 0) {
                                    return c.substring(name.length, c.length);
                                    }
                                }
                                return "";
                            }
                            
                        </script>
                    
            </div>
            
        </div>
            
    </body>
</html>