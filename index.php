<?php
try
{
    $pdo = new PDO('mysql:host=localhost;dbname=eur_dolar', 'root');
    if($pdo != NULL){
        //echo 'Połączenie zostało nawiązane';
    }else {
        echo 'PDO is empty', '<br><br>';
    }
}
catch(PDOException $e)
{
    echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage(), "<br>";
}
try {
    $sql = "SELECT * FROM eur_dolar.history";
    $result = $pdo->query($sql);
    if($result->rowCount()>0) {
        while($row = $result->fetch()) {
            $dateArray[] = $row["COL 1"];
            $eurbuyArray[] = $row["COL 2"];

            $eursellArray[] = $row["COL 3"];
        }
        unset($result);
    } else {
        echo 'No data in the database';
    }
} catch(PDOException $e) {
    die("Error");
}
unset($pdo);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--<style>
       .chartBox {
           width: 1000px;
       }
   </style>-->
    <style>
        * {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
        }
        .chartMenu {
            width: 100vw;
            height: 40px;
            background: #1A1A1A;
            color: rgba(54, 162, 235, 1);
        }
        .chartMenu p {
            padding: 10px;
            font-size: 20px;
        }
        .chartBox {
            width: 900px;
            padding: 20px;
            border-radius: 20px;
            border: solid 3px rgba(54, 162, 235, 1);
            background: white;
        }
    </style>
</head>
<body>
<div class = "chartBox">
    <input type="date" onchange="startOfDay(this)" value="2020-01-01" min="2020-01-01" max="2020-11-21">
    <input type="date" onchange="endOfDay(this)" value="2020-11-21" min="2020-01-01" max="2020-11-21">
    <canvas id="myChart"></canvas>
</div>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>

<script>
    const dateArrayJS = <?php echo json_encode($dateArray)?>;
    // console.log(dateArrayJS);

    const dateChartJs = dateArrayJS.map((day,index) => {
        let dayjs = new Date(day);
        return dayjs.setHours(0, 0, 0, 0)
        // return dayjs
    });
    console.log(dateChartJs)
    // setup
    const data = {
        labels: dateChartJs,
        datasets: [{
            label: '# EUR buy',
            data: <?php echo json_encode($eurbuyArray);?>,
            backgroundColor: [
                'rgba(255, 26, 104, 0.2)',

            ],
            borderColor: [
                'rgba(255, 26, 104, 1)',

            ],
            borderWidth: 1
        }]
    };

    // config
    const config = {
        type: 'bar',
        data,
        options: {
            scales: {
                x: {
                    type: 'time',
                    min: '2020-01-01',
                    max: '2020-11-21',
                    time: {
                        unit: 'day'
                    }
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    };
    const myChart = new Chart(
        document.getElementById('myChart'),
        config
    );
    function startOfDay(date) {
        const startDate = new Date(date.value);
        console.log(startDate.setHours(0, 0, 0, 0));
        myChart.config.options.scales.x.min = startDate.setHours(0, 0, 0, 0);
        myChart.update();
    }
    function endOfDay(date) {
        const endDate = new Date(date.value);
        console.log(endDate.setHours(0, 0, 0, 0));
        myChart.options.scales.x.max = endDate.setHours(0, 0, 0, 0);
        myChart.update();
    }

    // render init block

</script>

</body>
</html>