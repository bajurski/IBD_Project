<?php
try
{
    $pdo = new PDO('mysql:host=localhost;dbname=eur_dolar', 'root');
    if($pdo != NULL){
    }else {
        echo 'PDO is empty', '<br><br>';
    }
}
catch(PDOException $e)
{
    echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage(), "<br>";
}
?>
