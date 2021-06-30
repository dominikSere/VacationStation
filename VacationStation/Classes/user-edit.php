<?php


session_start();

// Check if the user is logged in, otherwise redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

if($_SESSION["is_admin"] == true){
  include '../Include/admin_navBar.php';
} else {
  include '../Include/navBar.php';
}

// Include config file
require_once "../Config/config.php";


$sql = "SELECT * FROM users WHERE id = ?";

if($stmt = $mysqli->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("i", $param_id);

        // Set parameters
        $param_id = $_SESSION["id"];

        // Attempt to execute the prepared statement
        if($stmt->execute()){
            $result = $stmt->get_result();

            if($result->num_rows == 1){
                /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                $row = $result->fetch_array(MYSQLI_ASSOC);

                // Retrieve individual field value
                $id = $row["id"];
                $username = $row["username"];
                $email = $row["email"];

            } else{
                // URL doesn't contain valid id parameter. Redirect to error page
                header("location: error.php");
                exit();
            }

        } else{
            echo "Something went wrong. Please try again later.";
        }
    }

	 // Close statement
    $stmt->close();

    // Close connection
    $mysqli->close();

    ?>
	<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <link href="../Css/user-edit.css" rel="stylesheet" type="text/css"/>
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col" id="useredit">
                    <div class="page-header">
                        <h1>Edit your profile</h1>
                        <a class="btn btn-primary" href='update.php'><span class="glyphicon glyphicon-pencil"></span> Edit</a>
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <p class="form-control-static"><?php echo $row["username"]; ?>
                    </div>
                    <div class="form-group">
                        <label>Email address</label>
                        <p class="form-control-static"><?php echo $row["email"]; ?>
                    </div>
                    <a href="reset-password.php" class="btn btn-primary">Reset Password</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
