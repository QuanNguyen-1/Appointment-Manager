<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search For an Appointment by Date</title>
    <link rel="stylesheet" href="./styles.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body class="body">
    <div class="container-fluid">
        <h1 class="title text-center">Search By Date</h1>
        <!-- the form is being sent to this same page -->
        <form id="date-form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">
            <div id="dateInput" class="input-group">
                <label for="searchDate" class="input-group-text">Enter a Date:</label>
                <input id="searchDate" type="date" class="form-control" name="dateInput" required />
                <button id="dateSearchBtn" class="btn" type="submit">Search</button>
            </div>
        </form>
        <a href="./index.html" class="text-decoration-none">
            <button class="btn" id="date-return-home">Return Home</button>
        </a>
        <?php
            //use functions from functions.php and initalize variables
            require "functions.php";
            $date = $dateErr = "";
            //when the the form is submitted and the method is post, run this php
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                if(empty($_POST["dateInput"])){
                    $dateErr = "Date is required";
                } else {
                    $date = cleanInput($_POST["dateInput"]);
                    $host = "localhost";
                    $dbname = "test_appointments";
                    $username = "root";
                    $password = "";
                    
                    //connect to mysql
                    $conn = new mysqli($host, $username, $password, $dbname);

                    if($conn->connect_error){
                        die("Connection failed: " . $conn->connect_error);
                    }

                    //prepare to select from appointments based on date and ordered by time
                    $stmt = $conn->prepare("SELECT * FROM appointments WHERE date=? ORDER BY time");
                    $stmt->bind_param("s",$date);
                    $stmt->execute();

                    $result = $stmt->get_result();
                    
                    //if there are more than 0 rows selected, return the rows into table format
                    if($result->num_rows > 0){
                        echo "<div class='table-responsive'>
                                <table id='dateTable' class='table table-bordered'>
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
                        while($rows = $result->fetch_assoc()){
                            echo "<tr>
                                    <td>" . $rows["id"] . "</td>
                                    <td>" . $rows["name"] . "</td>
                                    <td>" . $rows["number"] . "</td>
                                    <td>" . $rows["date"] . "</td>
                                    <td>" . $rows["time"] . "</td>
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
            echo "<div><h3 class=`text-cemter mt-5'>$dateErr</h3></div>";
        ?>
    </div>
</body>
</html>