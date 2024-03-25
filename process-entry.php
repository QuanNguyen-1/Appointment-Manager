<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title>Appointment has been recorded</title>
</head>
<body class="body">
    <div class="container-fluid">
        <h1 class="title text-center">Appointment has been recorded</h1>
        <?php 

            require "functions.php";

            //initalize variables
            $name = $number = $date = $time = "";

            //cleaning the inputs by trimming white space and convert special chars to html entities
            $name = cleanInput($_POST["name"]);
            $number = cleanNum(cleanInput($_POST["number"]));
            $date = cleanInput($_POST["date"]);
            $time = cleanInput($_POST["time"]);

            //if the input name, date, and time are not empty, then insert into table
            if (!empty($name) && !empty($time) && !empty($date)){
                $host = "localhost";
                $dbname = "test_appointments";
                $username = "root";
                $password = "";
                
                //connect to db
                $conn = new mysqli($host, $username, $password, $dbname);

                //check connection
                if($conn->connect_error){
                    die("Connection failed: " . $conn->connect_error);
                }

                //prepare to insert into table
                $stmt = $conn->prepare("INSERT INTO appointments (name, number, date, time) VALUES (?, ?, ?, ?)");

                //bind and execute
                $stmt->bind_param("ssss", $name, $number, $date, $time);
                $stmt->execute();

                //close stmt and the connection
                $stmt->close();
                $conn->close();

                }
                

        ?>
        <a href="./index.html" class="text-decoration-none">
            <button class="btn" id="returnHome">Return Home</button>
        </a>
    </div>

</body>
</html>

    