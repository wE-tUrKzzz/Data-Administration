<?php
include("connect.php");

// Get sorting and filter parameters
$sortColumn = $_GET['sortColumn'] ?? 'flightNumber';
$sortOrder = $_GET['sortOrder'] ?? 'ASC';
$selectedAirlineType = $_GET['airlineSelect'] ?? '';

$airlineQuery = "SELECT * FROM flightlogs";

if ($selectedAirlineType != '') {
  $airlineQuery = $airlineQuery . " WHERE aircraftType = '$selectedAirlineType'";
}

if ($sortColumn != '') {
  $airlineQuery = $airlineQuery . " ORDER BY $sortColumn";

  if ($sortOrder != '') {
    $airlineQuery = $airlineQuery . " $sortOrder";
  }
}

$airlineResults = executeQuery($airlineQuery);

$newSortOrder = ($sortOrder == 'ASC') ? 'DESC' : 'ASC';

$airlineTypesQuery = "SELECT DISTINCT aircraftType FROM flightlogs";
$airlineTypesResult = executeQuery($airlineTypesQuery);
?>


<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PUP | Airport Flight Log</title>
  <link rel="icon" href="favicon.ico" type="image/x-icon">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="Icon" type="image/png" href="PUPLogo.png">
</head>
<style>

body{
  background-color: #f8c006;
}

</style>
<body>

  <div class="container text-center">
    <div class="card py-5 mt-5 mb-5 shadow-lg rounded-4" style="background-color: #890115">
      <div class="row">
        <div class="col">
          <img src = "PUPLOGO.png" style="width:auto; height: 150px;">
          <h1 class="display-1" style="color:white">PUP Airport Flight Log</h1>
        </div>
      </div>
    </div>
  </div>

  <div class="container text-center">
    <div class="card py-3 mt-5 mb-3 shadow-lg rounded-4">
      <div class="row">
        <div class="col">
          <h3 class="display-5">History</h3>
        </div>
      </div>

      <div class="row mt-3 justify-content-center">
        <div class="col-auto">
          <div class="d-flex flex-row align-items-center">
            <label for="airlineSelect" class="me-2">Airline Type</label>
            <form method="get" action="" class="d-flex">
              <select id="airlineSelect" name="airlineSelect" class="form-control me-2" style="width: auto;">
                <option value="">Any</option>
                <?php

                while ($row = mysqli_fetch_assoc($airlineTypesResult)) {
                  $selected = ($row['aircraftType'] == $selectedAirlineType) ? 'selected' : '';
                  echo "<option value='{$row['aircraftType']}' {$selected}>{$row['aircraftType']}</option>";
                }
                ?>
              </select>
              <button type="submit" class="btn btn-primary">Filter</button>
            </form>
          </div>
        </div>
      </div>

      <div class="container my-4">
        <div class="row">
          <div class="col">
            <div class="table-responsive">
              <table class="table table-hover mt-3">
                <thead class="table">
                  <tr>
                    <th scope="col"><a href="?sortColumn=flightNumber&sortOrder=<?php echo $newSortOrder; ?>">Flight
                        Number</a></th>
                    <th scope="col"><a href="?sortColumn=airlineName&sortOrder=<?php echo $newSortOrder; ?>">Airline
                        Name</a></th>
                    <th scope="col"><a href="?sortColumn=aircraftType&sortOrder=<?php echo $newSortOrder; ?>">Airline
                        Type</a></th>
                    <th scope="col"><a
                        href="?sortColumn=passengerCount&sortOrder=<?php echo $newSortOrder; ?>">Passenger
                        Count</a></th>
                    <th scope="col"><a
                        href="?sortColumn=departureDatetime&sortOrder=<?php echo $newSortOrder; ?>">Departure Date</a>
                    </th>
                    <th scope="col"><a
                        href="?sortColumn=flightDurationMinutes&sortOrder=<?php echo $newSortOrder; ?>">Flight
                        Duration</a></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  if (mysqli_num_rows($airlineResults) > 0) {
                    while ($airlineRows = mysqli_fetch_assoc($airlineResults)) {
                      echo "<tr>
                          <td>{$airlineRows['flightNumber']}</td>
                          <td>{$airlineRows['airlineName']}</td>
                          <td>{$airlineRows['aircraftType']}</td>
                          <td>{$airlineRows['passengerCount']}</td>
                          <td>{$airlineRows['departureDatetime']}</td>
                          <td>" . floor($airlineRows['flightDurationMinutes'] / 60) . "h " . ($airlineRows['flightDurationMinutes'] % 60) . "m</td>
                        </tr>";
                    }
                  } else {
                    echo "<tr><td colspan='6'>No records found</td></tr>";
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>

</html>