<?php

$page = $_SERVER['PHP_SELF'];
$sec = "60";


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "thermo";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

//$sql_select = "SELECT id, time, td1 FROM thermo ORDER  BY id DESC";
//$result_db = $conn->query($sql_select);

$sql_select2 = "SELECT id, time, td1 FROM thermo ORDER  BY id DESC LIMIT 1";

$result_db2 = $conn->query($sql_select2);

$sql_select_ustavka = "SELECT id, ustavka, creat_date FROM ustavka ORDER  BY id DESC LIMIT 1";

$result_db_ustavka = $conn->query($sql_select_ustavka);

//header('Content-Type: text/html; charset=utf-8');

//$page = $_SERVER['PHP_SELF'];
//$sec = "60";

?>

<html>

<head>

    <meta http-equiv="refresh" content="<?php echo $sec?>;URL='<?php echo $page?>'">
    <meta charset=utf-8"/>
    <title> Температура серверная</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
            integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
            crossorigin="anonymous"></script>
    <!--    <meta http-equiv="refresh" content="--><?php //echo $sec?><!--;URL='--><?php //echo $page?><!--'">-->
</head>

<body>

<?php
date_default_timezone_set('Asia/Tashkent');
$now = date('Y-m-d H:i:s');
//echo $now;

$ch = curl_init('http://192.168.0.104:8080/thermo.cgi?t1');

curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);

curl_setopt($ch, CURLOPT_USERPWD, 'sait:152037');

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$result = curl_exec($ch);

curl_close($ch);

preg_match("/thermo_result\('ok', (\d+), (\d+)\);/", $result, $matches);

if ($result_db2->num_rows == 0) {
    $sql_insert = "INSERT INTO thermo (time, td1) VALUES ('$now','$matches[1]')";
    $conn->query($sql_insert);
} else {
    while ($row = mysqli_fetch_assoc($result_db2)) {
        $last_s = date("s", strtotime($row["time"]));
        $last_m = date("i", strtotime($row["time"]));
        $last_h = date("H", strtotime($row["time"]));
        $last_s += (60 * $last_m);
        $last_s += (3600 * $last_h);
        $now_s = date('s');
        $now_m = date('i');
        $now_h = date('H');
        $now_s += (60 * $now_m);
        $now_s += (3600 * $now_h);
        $diff = $now_s - $last_s;
        $ustavka = 60;
        if ($result_db_ustavka->num_rows > 0){
            while ($row2 = mysqli_fetch_assoc($result_db_ustavka)){
                $ustavka = $row2["ustavka"];
            }
        }
        //echo $ustavka;
        if (isset($_POST['send'])) {
            $sekund = $_POST['sekund'];
            $sekund += ($_POST['minut'] * 60);
            $sekund += ($_POST['soat'] * 3600);
            $ustavka = $sekund;

            if ($sekund > 0){
                $sql_insert = "INSERT INTO ustavka (ustavka, creat_date) VALUES ('$ustavka','$now')";
                $conn->query($sql_insert);
            }
        }
        if ($diff >= $ustavka) {
            $sql_insert_ustavka = "INSERT INTO thermo (time, td1) VALUES ('$now','$matches[1]')";
            $conn->query($sql_insert_ustavka);
        }
        //echo $diff;
        //echo date_format($row["time"], 'g:i A');
        //echo "id: " . $row["id"]. " - date: " . $row["time"]. " " . $row["td1"]. "<br>";
    }
}

?>

<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
    <ul class="navbar-nav">
        <li class="nav-item active">
            <a class="nav-link" href="index.php">Главная</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="table.php">Таблица</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="graph.php">Тренд</a>
        </li>
    </ul>
</nav>

<div class="containter" style="margin-top: 200px">
    <div class="row">
        <div class="col-md-6 offset-1 text-center">
            <h1 style="font-size: 75px;" class="text-success">
                <?php
                echo 'Температура ' . ' <br> ';
                ?>
            </h1>
            <br>
            <h1 style="font-size: 150px" class="text-success">
                <?php
                echo $matches[1];
                ?>
            </h1>
        </div>
        <div class="col-md-2 offset-1 text-center">
            <h5>Уставка времени записать в базу данных</h5>
            <form action="" method="post">
                <div class="form-group">
                    <label for="soat">Час:</label>
                    <input type="text" class="form-control" id="soat" placeholder="0 - 24" name="soat">
                </div>
                <div class="form-group">
                    <label for="minut">Минут:</label>
                    <input type="text" class="form-control" id="minut" placeholder="0 - 60" name="minut">
                </div>
                <div class="form-group">
                    <label for="sekund">Секунд:</label>
                    <input type="text" class="form-control" id="sekund" placeholder="0 - 60" name="sekund">
                </div>
                <button type="submit" class="btn btn-success">OK</button>
            </form>
        </div>
    </div>
</div>

</body>

</html>