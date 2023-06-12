<!DOCTYPE html>

<html lang="en">
<!-- Description: Assignment 3 -->
<!-- Author: Adrian Sim Huan Tze -->
<!-- Date: 19th May 2022 -->

<head>
    <title>Cosy Kangaroo - About</title>
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
                    <li class="nav-item active"><a href="about.php" class="nav-link">About</a></li>
                    <li class="nav-item"><a href="login.php" class="nav-link">Log In</a></li>
                    <li class="nav-item"><a href="loginAdmin.php" class="nav-link">Admin</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <h1>About Cosy Kangaroo</h1>

    <div class="about-container">
        <p>
            <img src="images/glenferrieRoad.jpg">
            The Cosy Kangaroo is a café/restaurant on Glenferrie Road that has recently
            acquired the next-door property in order to expand its business. From a capacity of
            about 50 people, the expansion will now allow the Cosy Kangaroo to host app. 150
            customers.
        </p>
        <p>
            <img src="images/restaurant.jpg">
            The day-to-day operations of the Cosy Kangaroo were so far organized in a very
            low-tech, mostly manual fashion, in particular on taking orders from guests, passing
            the orders on to the kitchen, accounting etc. The owners of the Cosy Kangaroo,
            however, realise that the way the staff was working until now would not scale to the
            new capacity of the café/restaurant. Therefore, they are thinking of introducing an
            information system to assist in their daily operations.
        </p>
        <p>
            <img src="images/allFood.png">
            Other restaurants incorporated further features into their information system. The
            owners of the Cosy Kangaroo, however, decided not to consider any of these
            features for now, but this may be an option in the future.
            In order to facilitate a tender process, the owners of the Cosy Kangaroo are now
            faced with the task of writing up a more detailed specification that clearly states the
            goals and requirements for the restaurant information system to be developed.
        </p>
    </div>

    <?php include 'footer.php' ?>

    <script src="js/jquery.min.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
</body>

</html>