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

// Define variables and initialize with empty values
$username = $email = "";
$username_err = $email_err = "";




// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){

  // Get hidden input value
    $id = $_POST["id"];


    // Validate name
    $input_username = trim($_POST["username"]);
    if(empty($input_username)){
        $username_err = "Please enter a username.";
    } else{

      // Prepare a select statement
      $sql = "SELECT id FROM users WHERE username = ? AND id != ?";

      if($stmt = $mysqli->prepare($sql)){
          // Bind variables to the prepared statement as parameters
          $stmt->bind_param("si", $param_username, $param_id);

          // Set parameters
          $param_username = $input_username;
          $param_id = $id;

          // Attempt to execute the prepared statement
          if($stmt->execute()){
              // store result
              $stmt->store_result();

              if($stmt->num_rows == 1){
                  $username_err = "This username is already taken.";
              } else{
                  $username = $input_username;
              }
          } else{
              echo "Something went wrong. Please try again later.";
          }

          // Close statement
          $stmt->close();
      }
    }

    // Validate email
    $input_email = trim($_POST["email"]);
    if(empty($input_email)){
        $email_err = "Please enter an email address.";
    } else{

      // Prepare a select statement
      $sql = "SELECT id FROM users WHERE email = ? AND id != ?";

      if($stmt = $mysqli->prepare($sql)){
          // Bind variables to the prepared statement as parameters
          $stmt->bind_param("si", $param_email, $param_id);

          // Set parameters
          $param_email = $input_email;
          $param_id = $id;

          // Attempt to execute the prepared statement
          if($stmt->execute()){
              // store result
              $stmt->store_result();

              if($stmt->num_rows == 1){
                  $email_err = "This email is already taken.";
              } else{
                  $email= $input_email;
              }
          } else{
              echo "Something went wrong. Please try again later.";
          }

          // Close statement
          $stmt->close();
      }
    }

    // Check input errors before inserting in database
    if(empty($username_err) && empty($email_err)){
        // Prepare an update statement
        $sql = "UPDATE users SET username=?, email=?, updated_at=now() WHERE id=?";

        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssi", $param_username, $param_email, $param_id);

            // Set parameters
            $param_username = $username;
            $param_email = $email;
            $param_id = $id;

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Records updated successfully. Redirect to landing page
                header("location: user-edit.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }

        // Close statement
        $stmt->close();
    }

    // Close connection
    $mysqli->close();
} else{
    // Check existence of id parameter before processing further
    if(isset($_SESSION["id"]) && !empty($_SESSION["id"])){
        // Get id from session
          $id =  $_SESSION["id"];

        // Prepare a select statement
        $sql = "SELECT * FROM users WHERE id = ?";
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("i", $param_id);

            // Set parameters
            $param_id = $id;

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                $result = $stmt->get_result();

                if($result->num_rows == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = $result->fetch_array(MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $username = $row["username"];
                    $email = $row["email"];

                } else{
                    // URL doesn't contain valid id. Redirect to error page
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
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link href="../Css/update.css" rel="stylesheet" type="text/css"/>
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
                <div class="col" id="updateRecord">
                    <div class="page-header">
                        <h2>Update Record</h2>
                    </div>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                            <span class="help-block"><?php echo $username_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                            <label>Email address</label>
                            <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
                            <span class="help-block"><?php echo $email_err;?></span>
                        </div>

                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="user-edit.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
