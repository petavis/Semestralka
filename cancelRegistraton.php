<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Windsurfing</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
          integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="css/1.css">
    <script type="text/javascript">
        function  success() {
            alert("Zmazanie bolo úspešné budete presmerovaný na hlavnú stránku");
            window.location.href = "index.html";
        }
    </script>

    <?php include_once('dbconnect.php') ?>
</head>
<body>

<?php
$database = new dbConnection();
$kurzy = $database->nacitajKurzyZDB();
$message = 'Užívateľ vymazaný';

if (isset($_POST['remove'])) {
    if ($database->odhlasZkurzu($_POST['email'], $_POST['kurz']) === false) {
        $message = 'Zle zadané hodnoty';
    }
}
?>

<h1>


</h1>

<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
    <!-- Brand -->
    <a class="navbar-brand" href="#"><img src="img/logo.jpg" alt="logo"></a>
    <!-- Toggler/collapsibe Button -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Navbar links -->
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <!-- Links -->
        <ul class="navbar-nav">
            <li class="nav-item mx-auto">
                <a class="nav-link" href="index.html">Domov</a>
            </li>
            <li class="nav-item mx-auto">
                <a class="nav-link" href="galeria.html">Galéria</a>
            </li>
            <li class="nav-item mx-auto">
                <a class="nav-link" href="registracia.php">Registrácia</a>
            </li>

        </ul>
    </div>
</nav>

<div class="wrapper">
    <div class="registration_form">
        <div class="title">
           Odhlasenie
        </div>

        <form method="post">
            <div class="form_wrap">
                <div class="input_wrap">
                    <label for="email">Email </label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="input_wrap">
                    <select name="kurz" id="kurz">
                        <?php
                        while ($row = mysqli_fetch_array($kurzy, MYSQLI_BOTH)) {
                            echo "<option value=".$row['meno'].">".$row['meno']."</option>";
                        }
                        ?>
                    </select>
                </div>

                <p>
                    <?php
                    if(isset($_POST['remove'])) {
                        echo $message;
                    }
                    ?>
                </p>

                <div class="input_wrap">
                    <input type="submit" name="remove" value="Odhlas z kurzu" class="submit_btn">
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx"
        crossorigin="anonymous"></script>


</body>
</html>
