<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <style type="text/css">
  .topnav {
    overflow: hidden;
    background-color: rgba(0, 104, 255, 0.8);
  }

  .topnav a {
    float: left;
    color: #f2f2f2;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
    font-size: 17px;
  }

  .topnav a:hover {
    color: black;
  }

  .logout {
    background-color: rgba(255, 0, 0, 0.74);
    color: black;
  }
  </style>
</head>
<body>
  <div class="topnav">
    <a href="admin-userlist.php">Admin Zone</a>
    <a href="user-edit.php">Edit User</a>
    <a href="logout.php" style="float:right" class="logout">Log Out</a>
    <a style="float:right">Welcome, <?php echo ($_SESSION["username"]); ?></a>
  </div>
</body>
</html>
