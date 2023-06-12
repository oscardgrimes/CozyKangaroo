<html lang="en">
<!-- Description: Assignment 3 -->
<!-- Author: Adrian Sim Huan Tze -->
<!-- Date: 11th May 2022 -->
<!-- Validated: =-->

<head>
    <title>Cosy Kangaroo - Log In</title>
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
                    <li class="nav-item active"><a href="login.php" class="nav-link">Log In</a></li>
                    <li class="nav-item"><a href="loginAdmin.php" class="nav-link">Admin</a></li>
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


    function chkEmail($input)
    {
        $emailOK = false;
        $email_msg = "";
        $email = $input;
        // validate the format of the email using regular expresssion
        if (checkNotEmpty($input)) {
            $email = strval($email);
            $pattern = "/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/";
            if (preg_match($pattern, $email) == 1) {
                $emailOK = true;
            } else {
                $emailOK = false;
                $email_msg = "The email format is not valid. Please try again!";
            }
        } else {
            $emailOK = false;
            $email_msg = "Email input cannot be empty. Please try again!";
        }
        return [$email_msg, $emailOK, $email];
    }

    function ChkEmailPasswordForLogin($emailInput, $passwordInput)
    {
        $email = $emailInput;
        $login_password = $passwordInput;
        $login_msg = "";
        $LoginOK = false;
        $error = "email";
        $profile = "";

        [$login_msg, $LoginOK, $email] = chkEmail($email);

        if ($LoginOK) {
            $db = new Database();
            // Create connection
            $db->createConnection();

            $email = mysqli_escape_string($db->getConnection(), $email);
            $login_password = mysqli_escape_string($db->getConnection(), $login_password);
            $login_password = substr($login_password, 0, 20);

            // check if the input email already exists or not by getting them from the database
            $sql = "SELECT * FROM customer WHERE cust_email = ?";

            $prepared_stmt = @mysqli_prepare($db->getConnection(), $sql);

            //Bind input variables to prepared statement
            @mysqli_stmt_bind_param($prepared_stmt, 's', $email);

            //Execute prepared statement
            @mysqli_stmt_execute($prepared_stmt);

            // Get resultset
            $queryResult =  @mysqli_stmt_get_result($prepared_stmt)
                or die("<p>Unable to select from database table</p>");

            // Close the prepared statement
            @mysqli_stmt_close($prepared_stmt);

            $row = mysqli_num_rows($queryResult);

            if ($row <= 0) {
                $error = "email";
                $login_msg = "Cannot find your account.";
                $LoginOK = false;
                $profile = "";
            } else if ($row == 1) {
                $row = mysqli_fetch_row($queryResult);
                if ($row[2] == $login_password) {
                    $LoginOK = true;
                    $profile = new Customer($row[3], $row[1], $row[2], $row[0]);
                } else {
                    $error = "password";
                    $login_msg = "Incorrect Password. Please try again.";
                    $LoginOK = false;
                }
            }

            $db->closeConnection();
        }


        return [$login_msg, $LoginOK, $email, $error, $profile];
    }

    $btnclicked = false;
    if (!empty($_POST["login"])) {
        $btnclicked = true;
        [$login_msg, $loginOk, $email_previous, $errorfield, $profile] =
            ChkEmailPasswordForLogin($_POST["email"], $_POST["login_password"]);

        if ($loginOk) {
            session_start();
            // Set session variables
            $_SESSION['profile'] = serialize($profile);
            header('Location: menupage.php');
        }
    } else {
        $btnclicked = false;
    }
    ?>

    <form method="post" action="login.php" novalidate="novalidate">
        <fieldset>
            <legend>Log In</legend>

            <label for="email">Email:</label> </br>
            <input type="email" name="email" id="email" placeholder="Enter your email" required="required" <?php
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