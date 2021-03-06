<?php
require 'database.php';

$id = null;
if (!empty($_GET['id'])) {
    $id = $_REQUEST['id'];
}

if (null == $id) {
    header("Location: index.php");
}

if (!empty($_POST)) {
    // keep track validation errors
    $nameError = null;
    $emailError = null;
    $passwordError = null;

    // keep track post values
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role_id = $_POST['role_id'];

    // validate input
    $valid = true;
    if (empty($name)) {
        $nameError = 'Please enter Name';
        $valid = false;
    }

    if (empty($email)) {
        $emailError = 'Please enter Email Address';
        $valid = false;
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailError = 'Please enter a valid Email Address';
        $valid = false;
    }

    if (empty($password)) {
        $passwordError = 'Please enter password';
        $valid = false;
    }

    // update data
    if ($valid) {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE user  set name = ?, email = ?, password = ?, role_id = ? WHERE id = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($name, $email, $password, $role_id, $id));
        Database::disconnect();
        header("Location: index.php");
    }
} else {
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM user_data where id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($id));
    $data = $q->fetch(PDO::FETCH_ASSOC);
    $name = $data['name'];
    $email = $data['email'];
    $password = $data['password'];
    $role_id = $data['role_id'];

    Database::disconnect();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/signin.css" rel="stylesheet">
    <link rel="shortcut icon " href="img/favicon.ico" type="image/x-icon">
    <link type="text/css" href="css/dropdown_menu.css" rel="stylesheet">
    <link type="text/css" href="css/css_user.css" rel="stylesheet">
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</head>

<body>
<div id="lable_sign" align="left"> <a href="users.php"> <img src="/img/image7.png"></a>

</div>
<div id="container_for_user_1">

    <div id="container_for_user_2">

        <form class="form-horizontal form-signin" action="update.php?id=<?php echo $id ?>" method="post">
            <div class="control-group <?php echo !empty($nameError) ? 'error' : ''; ?>">
                <h3>Update a User</h3>
                <label class="control-label">Name</label>
                <div class="controls">
                    <input name="name" type="text" placeholder="Name" value="<?php echo !empty($name) ? $name : ''; ?>">
                    <?php if (!empty($nameError)): ?>
                        <span class="help-inline"><?php echo $nameError; ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="control-group <?php echo !empty($emailError) ? 'error' : ''; ?>">
                <label class="control-label">Email Address</label>
                <div class="controls">
                    <input name="email" type="text" placeholder="Email Address"
                           value="<?php echo !empty($email) ? $email : ''; ?>">
                    <?php if (!empty($emailError)): ?>
                        <span class="help-inline"><?php echo $emailError; ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="control-group <?php echo !empty($passwordError) ? 'error' : ''; ?>">
                <label class="control-label">Password</label>
                <div class="controls">
                    <input name="password" type="text" placeholder="Password"
                           value="<?php echo !empty($password) ? $password : ''; ?>">
                    <?php if (!empty($passwordError)): ?>
                        <span class="help-inline"><?php echo $passwordError; ?></span>
                    <?php endif; ?>
                </div>
                <br>
                <?php

                $pdo = Database::connect();
                $sql = 'Select * from role order by name desc';
                $roles = $pdo->query($sql);
                Database::disconnect();
                ?>
                <select name="role_id" class="form-control">
                    <?php foreach ($roles as $role)
                        echo '<option ' . ($role['id'] == $role_id ? 'selected ' : ' ') . 'value="' . $role['id'] . '">' . $role['name'] . '</option>';
                    ?>
                </select>

            </div>
            <br><br><br>
            <div class="form-actions">
                <button type="submit" class="btn btn-success">Update</button>
                <a class="btn btn-info" href="index.php">Back</a>
            </div>
        </form>
    </div>

</div> <!-- /container -->
</body>
</html>