<?php
include("connect.php");

if (isset($_POST['btnSubmit'])) {
    $comment = $_POST['comment'];
    $comment = mysqli_real_escape_string($conn, $comment);

    $comment = "INSERT INTO comments (commentID, dateTime, Content, userID, postID) VALUES (NULL, CURTIME(), '$comment', 1, 1);";
    executeQuery($comment);

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if (isset($_GET['delete'])) {
    $commentID = intval($_GET['delete']); 
    $deleteQuery = "DELETE FROM comments WHERE commentID = $commentID";
    executeQuery($deleteQuery);

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Query to fetch user details and comments
$query = "
  SELECT Users.userName, userinfo.firstName, userinfo.lastName, provinces.name AS province, cities.name AS city, 
         comments.content, comments.dateTime, comments.commentID
  FROM Users
  LEFT JOIN userinfo ON Users.userID = userinfo.userID
  LEFT JOIN addresses ON userinfo.addressID = addresses.addressID
  LEFT JOIN provinces ON addresses.provinceID = provinces.provinceID
  LEFT JOIN cities ON addresses.cityID = cities.cityID
  LEFT JOIN comments ON Users.userID = comments.userID
";

// Query to fetch the name and dateTime for the user with userID 1
$userQuery = "
  SELECT Users.userName, userinfo.firstName, userinfo.lastName, comments.dateTime
  FROM Users
  LEFT JOIN userinfo ON Users.userID = userinfo.userID
  LEFT JOIN comments ON Users.userID = comments.userID
  WHERE Users.userID = 1
";

$result = executeQuery($query);
$userResult = executeQuery($userQuery);
$userData = mysqli_fetch_assoc($userResult); // Fetch data for userID 1
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>FaKeBook</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <style>
    .date-time {
        color: gray;
        font-size: 0.875em;
    }
    .user-profile-pic {
        border-radius: 50%;
        width: 50px;
        height: 50px;
    }
    .unknown-profile-pic {
        border-radius: 50%;
        width: 50px;
        height: 50px;
        background-color: lightgray; 
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: white;
    }
    .post-text {
        font-size: 1.25em;
        font-weight: bold;
        text-align: center;
        margin: 20px auto;
        padding: 20px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        background-color: black;
        color: white;
        width: 90%;
        max-width: 600px;
    }
  </style>
</head>
<body>
  <!-- Navbar with Facebook Logo -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">
        <img src="https://upload.wikimedia.org/wikipedia/commons/5/51/Facebook_f_logo_%282019%29.svg" alt="Facebook Logo" width="30" height="30" class="d-inline-block align-text-top">
        FaKeBook
      </a>
    </div>
  </nav>

  <!-- User Information for UserID 1 -->
  <div class="container mt-3">
    <div class="row align-items-center">
      <div class="col-auto"></div>
      <div class="col-auto">
        <!-- Unknown Profile Picture -->
        <div class="unknown-profile-pic">?</div>
      </div>
      <div class="col" style="margin-top:20px">
        <?php if ($userData) { ?>
          <h5><?php echo htmlspecialchars($userData["firstName"] . " " . $userData["lastName"]); ?></h5>
          <p class="date-time"><?php echo htmlspecialchars($userData["dateTime"]); ?></p>
        <?php } ?>
      </div>
    </div>
  </div>

  <!-- Centered Text Section -->
  <div class="text-center mt-3">
    <div class="post-text">
      "When everybody says werewolf but nobody asks how wolf?"
    </div>
  </div>

  <!-- Container for displaying user comments -->
  <div class="container mt-4">
    <div class="row">
      <!-- PHP Block to Display User Information and Comments -->
      <?php if (mysqli_num_rows($result) > 0) { ?>
        <?php while ($user = mysqli_fetch_assoc($result)) { ?>
          <div class="col-12">
            <div class="card rounded-4 shadow my-3 mx-5">
              <div class="card-body">
                <h5 class="card-title">
                  <?php echo htmlspecialchars($user["firstName"] . " " . $user["lastName"]); ?>
                </h5>
                <h6 class="card-subtitle mb-2 text-body-secondary">
                  <?php
                  if (!empty($user["city"])) {
                      echo htmlspecialchars($user["city"] . ", " . $user["province"]);
                  } else {
                      echo "Location not available";
                  }
                  ?>
                </h6>
                <p class="card-text">
                  <?php echo htmlspecialchars($user["content"]); ?>
                </p>
                <p class="date-time">
                  <?php echo htmlspecialchars($user["dateTime"]); ?>
                </p>
                <a href="?delete=<?php echo $user['commentID']; ?>" class="btn btn-danger btn-sm">Delete</a>
              </div>
            </div>
          </div>
        <?php } ?>
      <?php } ?>
    </div>
    <div class="row my-2">
      <div class="col-12">
        <div class="card p-4 text-center rounded-4 my-3 mx-5 shadow">
          <form method="POST">
            <textarea class="my-3 form-control" name="comment" placeholder="Comment"></textarea>
            <button class="my-2 btn btn-primary" name="btnSubmit">Submit Comment</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <footer class="facebook-footer bg-primary text-white py-4">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-auto">
          <p>&copy; 2024 Your Website. All Rights Reserved.</p>
        </div>
      </div>
      <div class="row justify-content-center">
        <div class="col-auto">
          <ul class="footer-links list-unstyled d-flex">
            <li class="mx-3"><a href="#" class="text-white text-decoration-none">Privacy</a></li>
            <li class="mx-3"><a href="#" class="text-white text-decoration-none">Terms</a></li>
            <li class="mx-3"><a href="#" class="text-white text-decoration-none">Ad Choices</a></li>
            <li class="mx-3"><a href="#" class="text-white text-decoration-none">Cookies</a></li>
            <li class="mx-3"><a href="#" class="text-white text-decoration-none">More</a></li>
          </ul>
        </div>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
