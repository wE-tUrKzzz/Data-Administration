<?php
include('connect.php');

$id = $_GET['id'];

if (isset($_POST['btnEdit'])) {
    $content = $_POST['content'];

    $editQuery = "UPDATE comments SET content='$content' WHERE commentID='$id'";
    executeQuery($editQuery);

    header('Location: ./');
}

$query = "SELECT * FROM comments 
LEFT JOIN userinfo ON comments.userID = userinfo.userID 
LEFT JOIN users ON comments.userID = users.userID 
WHERE comments.commentID = '$id';";

$result = executeQuery(query: $query);
$comment = mysqli_fetch_assoc($result);
?>

<doctype html>
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

            footer{
                margin-top: 200px;
            }

        </style>
    </head>

    <body>
        <!-- Navbar with Facebook Logo -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/5/51/Facebook_f_logo_%282019%29.svg"
                        alt="Facebook Logo" width="30" height="30" class="d-inline-block align-text-top">
                    FaKeBook
                </a>
            </div>
        </nav>

        <div class="container">
            <div class="row mt-5">
                <div class="col">
                    <div class="card shadow rounded-5 p-5 my-5 ">
                        <div class="h3 text-center">
                            Edit comment
                        </div>
                        <!-- Edit Comment Form (Example) -->
                        <div class="mt-5">
                            <h4>Edit Comment</h4>
                            <form method="POST">
                                   <input type="hidden" name="commentID" value="<?php echo $comment['commentID']; ?>">
                                <textarea class="form-control" id="commentContent" name="content"
                                    rows="4"><?php echo $comment['Content']; ?></textarea>

                                </div> <label for="commentContent" class="form-label"></label>

                                <button type="submit" name="btnEdit" class="btn btn-warning">Update
                                    Comment</button>
                            </form>
                        </div>

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
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script>
    </body>

    </html>