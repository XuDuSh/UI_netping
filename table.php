<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "thermo";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

$sql_select = "SELECT id, time, td1 FROM thermo ORDER  BY id DESC";
$result_db = $conn->query($sql_select);

?>

<html>

<head>

    <meta charset=utf-8"/>
    <title> Температура серверная</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
            integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
            crossorigin="anonymous"></script>
</head>

<body>

<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" href="index.php">Главная</a>
        </li>
        <li class="nav-item active">
            <a class="nav-link" href="table.php">Таблица</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="graph.php">Тренд</a>
        </li>
    </ul>
</nav>
<div class="container">
    <div class="row text-centre">
        <div class="col-md-8 offset-2" style="margin-top: 50px">
            <h2 class="text-center">Таблица температур</h2>
            <table class="table">
                <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Время</th>
                    <th>Градус</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if ($result_db->num_rows > 0) {
                    $cnt = 0;
                    while ($row = mysqli_fetch_assoc($result_db)) {
                        $cnt++;
                        if ($cnt == 100)
                            break;
                        ?>
                        <tr>
                            <td><?= $cnt?></td>
                            <td><?= $row["time"]?></td>
                            <td><?= $row["td1"]?></td>
                        </tr>
                        <?php
                    }
                }
                ?>

                </tbody>
            </table>
        </div>
    </div>
</div>

</body>

</html>