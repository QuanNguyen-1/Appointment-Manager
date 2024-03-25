<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search for an Appointment by Name</title>
    <link rel="stylesheet" href="./styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body class="body">
    
    <div class="container-fluid">
        <h1 class="title text-center">Search By Name</h1>
        <!-- the form is being sent to this same page -->
        <form id="name-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <div id="nameInput" class="input-group">
                <label for="searchName" class="input-group-text">Enter a Name:</label>
                <input id="searchName" type="text" class="form-control" name="nameInput" required />
                <button id="nameSearchBtn" class="btn" type="submit">Search</button>
            </div>
        </form>
        <a href="index.html" class="text-decoration-none">
            <button class="btn" id="name-return-home">Return Home</button>
        </a>
        <?php
        //use functions from functions.php and initalize variables
        require "functions.php";
        $nameInput = $nameErr = "";

        //when the the form is submitted and the method is post, run this php
        if($_SERVER['REQUEST_METHOD'] == "POST"){
            if(empty($_POST["nameInput"])){
                $nameErr = "Name is required";
            } else {
                //wildcard so if $name is anywhere in the name colum, that row is selected
                $nameInput = cleanInput($_POST["nameInput"]);
                $name = "%$nameInput%";
                $host = "localhost";
                $dbname = "test_appointments";
                $username = "root";
                $password = "";
                
                //connect to mysql
                $conn = new mysqli($host, $username, $password, $dbname);

                if($conn->connect_error){
                    die("Connection failed: " . $conn->connect_error);
                }
                //prepare to select from appointments based on name and ordered by date and then time
                $stmt = $conn->prepare("SELECT * FROM appointments WHERE name LIKE ? ORDER BY date, time");
                $stmt->bind_param("s",$name);
                $stmt->execute();

                $result = $stmt->get_result();

                //if there are more than 0 rows selected, return the rows into table format
                if($result->num_rows > 0){
                    echo "<div class='table-responsive'>
                        <table id='nameTable' class='table table-bordered'>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Phone Number</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>";
                    //loop through all the row results from the select query
                    while($row = $result->fetch_assoc()){
                        echo "<tr>
                                <td>" . $row["id"] . "</td>
                                <td>" . $row["name"] . "</td>
                                <td>" . $row["number"] . "</td>
                                <td>" . $row["date"] . "</td>
                                <td>" . $row["time"] . "</td>
                            </tr>";
                                }
                                echo "</tbody></table></div>";
                            } else {
                                //if no results from the select query, return message
                                echo "<div><h3 class='text-center mt-5'>0 Results</h3></div>";
                            }
            
                            $stmt->close();
                            $conn->close();
            }
        }
        echo "<div><h3 class=`text-cemter mt-5'>$nameErr</h3></div>";
        ?>
    </div>


</body>
</html>