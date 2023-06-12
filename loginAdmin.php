<html lang="en">
<!-- Description: Assignment 3 -->
<!-- Author: Adrian Sim Huan Tze -->
<!-- Date: 11th May 2022 -->
<!-- Validated: =-->

<head>
    <title>Cosy Kangaroo - Log In Admin</title>
    <meta charset="utf-8">
    <meta name="author" content="Adrian Sim Huan Tze">
    <meta name="description" content="Assignment 3">
    <meta name="keywords" content="food, order, reservation">
    <link rel="icon" href="images/companylogo.png">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="style/style.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg ftco_navbar ftco-navbar-light" id="ftco-navbar">
        <div class="container">
            <a class="navbar-brand" href="index.php"><img src='images/companylogo.png' alt='icon'><span class="company-name">Cosy Kangaroo</span></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="fa fa-bars"></span>
            </button>
            <div class="collapse navbar-collapse" id="ftco-nav">
                <ul class="navbar-nav ml-auto mr-md-3">
                    <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
                    <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
                    <li class="nav-item"><a href="login.php" class="nav-link">Log In</a></li>
                    <li class="nav-item active"><a href="loginAdmin.php" class="nav-link">Admin</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <?php
    include "classes/customer.php";

    function checkNotEmpty($field)
    {
        if (empty($field) == false) {
            return true;
        } else {
            return false;
        }
    }


    function chkUsername($input)
    {
        $usernameOK = false;
        $username_msg = "";
        $username = $input;
        // validate the format of the username using regular expresssion
        if (checkNotEmpty($input)) {
            $username = strval($username);
            $pattern = "/^[A-Za-z][A-Za-z0-9_]{4,29}$/";
            if (preg_match($pattern, $username) == 1) {
                $usernameOK = true;
            } else {
                $usernameOK = false;
                $username_msg = "The username format is not valid. Please try again!";
            }
        } else {
            $usernameOK = false;
            $username_msg = "Username input cannot be empty. Please try again!";
        }
        return [$username_msg, $usernameOK, $username];
    }

    function ChkUsernamePasswordForLogin($usernameInput, $passwordInput)
    {
        $username = $usernameInput;
        $login_password = $passwordInput;
        $login_msg = "";
        $LoginOK = false;
        $error = "email";

        [$login_msg, $LoginOK, $email] = chkUsername($username);

        if ($LoginOK) {
            $db = new Database();
            // Create connection
            $db->createConnection();

            $username = mysqli_escape_string($db->getConnection(), $username);
            $login_password = mysqli_escape_string($db->getConnection(), $login_password);
            $login_password = substr($login_password, 0, 20);

            // check if the input username already exists or not by getting them from the database
            $sql = "SELECT * FROM admin WHERE username = ?";

            $prepared_stmt = @mysqli_prepare($db->getConnection(), $sql);

            //Bind input variables to prepared statement
            @mysqli_stmt_bind_param($prepared_stmt, 's', $username);

            //Execute prepared statement
            @mysqli_stmt_execute($prepared_stmt);

            // Get resultset
            $queryResult =  @mysqli_stmt_get_result($prepared_stmt)
                or die("<p>Unable to select from database table</p>");

            // Close the prepared statement
            @mysqli_stmt_close($prepared_stmt);

            $row = mysqli_num_rows($queryResult);

            if ($row <= 0) {
                $error = "username";
                $login_msg = "Invalid credentials.";
                $LoginOK = false;
            } else if ($row == 1) {
                $row = mysqli_fetch_row($queryResult);
                if ($row[2] == $login_password) {
                    $LoginOK = true;
                } else {
                    $error = "password";
                    $login_msg = "Invalid credentials.";
                    $LoginOK = false;
                }
            }

            $db->closeConnection();
        }

        return [$login_msg, $LoginOK, $email, $error];
    }

    $btnclicked = false;
    if (!empty($_POST["login"])) {
        $btnclicked = true;
        [$login_msg, $loginOk, $email_previous, $errorfield] =
            ChkUsernamePasswordForLogin($_POST["username"], $_POST["login_password"]);

        if ($loginOk) {
            session_start();
            // Set session variables
            $_SESSION['admin'] = "Admin";
            header('Location: adminorderpage.php');
        }
    } else {
        $btnclicked = false;
    }
    ?>

    <form method="post" action="loginAdmin.php" novalidate="novalidate">
        <fieldset>
            <legend>Log In Admin</legend>

            <label for="username">Username:</label> </br>
            <input type="email" name="username" id="email" placeholder="Enter your username" required="required" <?php
                                                                                                                    if ($btnclicked) {
                                                                                                                        if (!$loginOk) {
                                                                                                                            echo "value = '" . $email_previous . "'";
                                                                                                                        }
                                                                                                                    }
                                                                                                                    ?> />
            <?php
            if ($btnclicked) {
                if ($errorfield == "email") {
                    echo "<p>" . $login_msg . "</p>";
                }
            }
            ?>
            </br>

            <label for="login_password">Password:</label> </br>
            <input type="password" name="login_password" id="login_password" placeholder="Enter your password" required="required" />
            <?php
            if ($btnclicked) {
                if ($errorfield == "password") {
                    echo "<p>" . $login_msg . "</p>";
                }
            }
            ?>
            </br>
        </fieldset>

        <input type="submit" value="Log In" name="login" />
        <input type="reset" value="Clear" />
    </form>

    <?php include "footer.php" ?>
    <script src="js/jquery.min.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
</body>

</html>