<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Appointments Today</title>
    <link rel="stylesheet" href="./styles.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/215a3ecc61.js" crossorigin="anonymous"></script>
</head>
<body class="body">
    <?php
        $todayDate = date("Y-m-d");
        $day = date("l");
        $todayInput = "";

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
        <h1 class="title text-center">Appointments Today: <?php echo $day . ", " . $todayDate;?></h1>
        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
            <button id="todaySearchBtn" class="btn" type="submit" name="todayInput" value="<?php echo $todayDate;?>">Search</button>
        </form>
        <a href="./index.html" class="text-decoration-none">
            <button class="btn" id="today-return-home">Return Home</button>
        </a>
        <?php
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                $todayInput = $_POST["todayInput"];
                
                require "connection.php";

                $stmt = $conn->prepare("SELECT * FROM appointments WHERE date=?");
                $stmt->bind_param("s", $todayInput);
                $stmt->execute();

                $result = $stmt->get_result();

                if($result->num_rows > 0){
                    echo "<div class='table-responsive'>
                            <table id='todayTable' class='table table-bordered'>
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
                    while($row = $result->fetch_assoc()){
                        echo "<tr>
                                <td>" . $row["name"] . "</td>
                                <td>" . $row["number"] . "</td>
                                <td>" . $row["date"] . "</td>
                                <td>" . $row["time"] . "</td>
                                <td>
                                    <a href='search-today.php?id=" . $row["id"] . "'><i class='fas fa-ban text-danger'></i></a>
                                </td>
                            </tr>";
                    }
                    echo "</tbody></table></div>";
                } else {
                    echo "<div><h3 class='text-center mt-5'>0 Results<h3></div>";
                }
                $stmt->close();
                require "disconnect.php";
            }
        ?>
    </div>
</body>
</html>