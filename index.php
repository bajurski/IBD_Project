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
    $sql = "SELECT * FROM history";
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
    <style>
        * {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
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
        .chartBox1 {
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
    <canvas id="eurBuyChart"></canvas>
</div><br>
<div class = "chartBox1">
    <input type="date" onchange="startOfDay1(this)" value="2020-01-01" min="2020-01-01" max="2020-11-21">
    <input type="date" onchange="endOfDay1(this)" value="2020-11-21" min="2020-01-01" max="2020-11-21">
    <canvas id="eurSellChart"></canvas>
</div>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>

<script>
    //Encoding EURbuy data from sql database
    const dateArrayJS = <?php echo json_encode($dateArray)?>;
    const dateChartJs = dateArrayJS.map((day,index) => {
        let dayjs = new Date(day);
        return dayjs.setHours(0, 0, 0, 0)
    });
    // setup for EURbuy chart
    const dataEurBuy = {
        labels: dateChartJs,
        datasets: [{
            label: '# EUR buy',
            data: <?php echo json_encode($eurbuyArray);?>,
            backgroundColor: [
                'rgba(26,125,255,0.2)',
            ],
            borderColor: [
                'rgb(20,59,255)',
            ],
            borderWidth: 1
        }]
    };
    // config for EURbuy table
    const configEurBuy = {
        type: 'bar',
        data: dataEurBuy,
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
    //Creating chart EURbuy
    const eurBuyChart = new Chart(
        document.getElementById('eurBuyChart'),
        configEurBuy
    );
    function startOfDay(date) {
        const startDate = new Date(date.value);
        console.log(startDate.setHours(0, 0, 0, 0));
        eurBuyChart.options.scales.x.min = startDate.setHours(0, 0, 0, 0);
        eurBuyChart.update();
    }
    function endOfDay(date) {
        const endDate = new Date(date.value);
        console.log(endDate.setHours(0, 0, 0, 0));
        eurBuyChart.options.scales.x.max = endDate.setHours(0, 0, 0, 0);
        eurBuyChart.update();
    }
//EURsell chart ========================================================================================================
    // setup for EURsell table
    const dataEurSell = {
        labels: dateChartJs,
        datasets: [{
            label: '# EUR sell',
            data: <?php echo json_encode($eursellArray);?>,
            backgroundColor: [
                'rgba(255,26,106,0.2)',
            ],
            borderColor: [
                'rgb(255,20,138)',
            ],
            borderWidth: 1
        }]
    };
    // config for EURsell table
    const configEurSell = {
        type: 'bar',
        data:dataEurSell,
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

    //Creating chart EURsell
    const eurSellChart = new Chart(
        document.getElementById('eurSellChart'),
        configEurSell
    );

    function startOfDay1(date) {
        const startDate = new Date(date.value);
        console.log(startDate.setHours(0, 0, 0, 0));
        eurSellChart.options.scales.x.min = startDate.setHours(0, 0, 0, 0);
        eurSellChart.update();
    }
    function endOfDay1(date) {
        const endDate = new Date(date.value);
        console.log(endDate.setHours(0, 0, 0, 0));
        eurSellChart.options.scales.x.max = endDate.setHours(0, 0, 0, 0);
        eurSellChart.update();
    }

</script>

</body>
</html>