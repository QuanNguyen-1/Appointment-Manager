<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search For an Appointment by Date</title>
    <link rel="stylesheet" href="./styles.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/215a3ecc61.js" crossorigin="anonymous"></script>
</head>
<body class="body">
    <?php
        //use functions from functions.php and initalize variables
        require "functions.php";
        $date = $dateErr = "";

        if (isset($_GET['id'])) {
            require "connection.php" ;

            $id = $_GET['id'];
            $stmt2 = $conn->prepare("DELETE FROM appointments WHERE id=?");
            $stmt2->bind_param("i", $id);
            $stmt2->execute();
            $stmt2->close();

            require "disconnect.php";
        }
    ?>
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

            //when the the form is submitted and the method is post, run this php
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                if(empty($_POST["dateInput"])){
                    $dateErr = "Date is required";
                } else {
                    $date = cleanInput($_POST["dateInput"]);
                    
                    require "connection.php";

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
                                            <th>Name</th>
                                            <th>Phone Number</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                            <tbody>";
                        //loop through all the row results from the select query
                        while($row = $result->fetch_assoc()){
                            echo "<tr>
                                    <td>" . $row["name"] . "</td>
                                    <td>" . $row["number"] . "</td>
                                    <td>" . $row["date"] . "</td>
                                    <td>" . $row["time"] . "</td>
                                    <td>
                                        <a href='search-date.php?id=" . $row["id"] . "'><i class='fas fa-ban text-danger'></i></a> 
                                    </td>
                                </tr>";
                        }
                        echo "</tbody></table></div>";
                    } else {
                        //if no results from the select query, return message
                        echo "<div><h3 class='text-center mt-5'>0 Results</h3></div>";
                    }

                    $stmt->close();
                    require "disconnect.php";
                }
            }
            echo "<div><h3 class=`text-cemter mt-5'>$dateErr</h3></div>";
        ?>
    </div>
</body>
</html>