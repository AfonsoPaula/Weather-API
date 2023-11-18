<?php

require_once 'inc/config.php';
require_once 'inc/api.php';

$city = 'Lisbon';
if(isset($_GET['city'])){
    $city = $_GET['city'];
}
$days = 5;

$results = Api::get($city, $days);

if($results['status'] == 'error'){
    echo $results['message'];
    exit;
}

$data = json_decode($results['data'], true);

// location data
$location = [];
$location['name'] = $data['location']['name'];
$location['region'] = $data['location']['region'];
$location['country'] = $data['location']['country'];
$location['current_time'] = $data['location']['localtime'];

// current weather data
$current = [];
$current['info'] = 'Neste momento:';
$current['temperature'] = $data['current']['temp_c'];
$current['condition'] = $data['current']['condition']['text'];
$current['condition_icon'] = $data['current']['condition']['icon'];
$current['wind_speed'] = $data['current']['wind_kph'];

// forecast weather data
$forecast = [];
foreach($data['forecast']['forecastday'] as $day){
    $forecast_day = [];
    $forecast_day['info'] = null;
    $forecast_day['date'] = $day['date'];
    $forecast_day['condition'] = $day['day']['condition']['text'];
    $forecast_day['condition_icon'] = $day['day']['condition']['icon'];
    $forecast_day['max_temp'] = $day['day']['maxtemp_c'];
    $forecast_day['min_temp'] = $day['day']['mintemp_c'];
    $forecast[] = $forecast_day;
}

function city_selected($city, $selected_city){
    if($city == $selected_city){
        return 'selected';
    }
    return '';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body class="bg-dark text-white">
    <div class="container-fluid">
        <div class="row justify-content-center mt-5">
            <div class="col-10 p-5 bg-light text-black">

                <div class="row">
                    <div class="col-9">
                        <h3>Weather for the city of  <strong><?= $location['name'] ?></strong></h3>
                        <p class="my-2">Region: <?= $location['region'] ?> | <?= $location['country'] ?> | <?= $location['current_time'] ?> | Forecast for <strong><?= $days ?></strong> days</p>
                    </div>
                    <div class="col-3 text-end">
                        <select class="form-select">
                            <option value="Lisbon"  <?= city_selected($city, 'Lisbon') ?>>Lisbon</option>
                            <option value="Madrid"  <?= city_selected($city, 'Madrid') ?>>Madrid</option>
                            <option value="Paris"   <?= city_selected($city, 'Paris') ?>>Paris</option>
                            <option value="London"  <?= city_selected($city, 'London') ?>>London</option>
                            <option value="Berlin"  <?= city_selected($city, 'Berlin') ?>>Berlin</option>
                            <option value="Maputo"  <?= city_selected($city, 'Maputo') ?>>Maputo</option>
                            <option value="Brasilia"<?= city_selected($city, 'Brasilia') ?>>Brasilia</option>
                            <option value="Luanda"  <?= city_selected($city, 'Luanda') ?>>Luanda</option>
                        </select>
                    </div>
                </div>

                <!-- current -->
                <?php
                    $weather_info = $current;
                    include 'inc/weather_info.php';
                ?>
                <!-- forecaste -->
                <?php foreach($forecast as $day) : ?>
                    <?php
                        $weather_info = $day;
                        include 'inc/weather_info.php';
                    ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
        const select = document.querySelector('select');
        select.addEventListener('change', (e) => {
            const city = e.target.value;
            window.location.href = `index.php?city=${city}`;
        })
    </script>

</body>
</html>