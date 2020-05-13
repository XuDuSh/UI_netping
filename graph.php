<!doctype html>
<html>
<head>
    <meta charset=utf-8"/>
    <title>Тренд</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
            integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
            crossorigin="anonymous"></script>
</head>
<body >

<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" href="index.php">Главная</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="table.php">Таблица</a>
        </li>

        <li class="nav-item active">
            <a class="nav-link" href="graph.php">Тренд</a>
        </li>
    </ul>
</nav>

<?Php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "thermo";

$connection = mysqli_connect($servername, $username, $password, $dbname);

if($stmt = $connection->query("SELECT time,td1 FROM thermo")){

    $php_data_array = Array(); // create PHP array
    while ($row = $stmt->fetch_row()) {
        $php_data_array[] = $row; // Adding to array
    }
}else{
    echo $connection->error;
}
//echo json_encode($php_data_array);

// Transfor PHP array to JavaScript two dimensional array
echo "<script>
        var my_2d = ".json_encode($php_data_array)."
</script>";
?>


<div id="curve_chart"></div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

    // Load the Visualization API and the corechart package.
    google.charts.load('current', {packages: ['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Время');
        data.addColumn('number', 'Градус');
        data.addColumn('number', '');
        data.addColumn('number', '');
        data.addColumn('number', '');
        data.addColumn('number', '');
        $size = my_2d.length;
        for(i = $size-50; i < my_2d.length; i++)
            data.addRow([my_2d[i][0], parseInt(my_2d[i][1]),parseInt(my_2d[i][2]),parseInt(my_2d[i][3]),parseInt(my_2d[i][4]),parseInt(my_2d[i][5])]);
        var options = {
            title: 'Тренд',
            curveType: 'function',
            width: 2000,
            height: 750,
            legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
        chart.draw(data, options);
    }
    ///////////////////////////////
</script>
</body></html>







