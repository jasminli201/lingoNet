<!-- ***************************************************************************************/
* Title: Bootstrap source code
* Author: Mark Otto, Jacob Thornton
* Date: 2021
* Code version: 4.6.0
* Availability: https://getbootstrap.com/
***************************************************************************************/ -->
<?php
require("../db/connectdb.php");
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Profile">
  <meta name="author" content="Jasmin Li, Rebecca Zhou">

  <title>Profile</title>
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
  <link rel="stylesheet" href="../css/layout.css" />
  <link rel="stylesheet" href="../css/content.css" />
  <link rel="stylesheet" href="../css/theme.css" />
</head>

<body>
  <?php
  // if user has logged in, then display 
  if (isset($_SESSION['email'])) {
  ?>
    <div class="page-container">
      <div class="content-wrap">
        <div id="header"></div>
        <br />

        <div class="content">
          <h1 class="display-4">Profile</h1>

          <div class="container">
            <div class="row">
              <div class="col-4">

                <div class="card border border-purple">
                  <!-- <img class="card-img-top" src="..." alt="Profile Picture"> -->
                  <div class="card-body">
                    <h5 class="card-title">
                      John Smith
                    </h5>
                    <div>Username: johnsmith</div>
                    <div>Age: 22</div>
                    <div>Email: johnsmith@gmail.com</div>
                    <div>Phone Number: 123-456-7890</div>
                    <button class="btn btn-purple" role="button">Edit</button>

                  </div>
                </div>

              </div>
              <div class="col">
                <div class="card border border-purple">
                  <div class="card-body">
                    <h5 class="card-title">
                      Languages
                    </h5>

                    <h6 class="card-subtitle mb-2">Can speak: </h6>
                    <p> English </p>
                    <h6 class="card-subtitle mb-2 ">Want to practice: </h6>
                    <p> Spanish </p>
                    <button class="btn btn-purple" role="button">Edit</button>

                  </div>
                </div>

                </br>

                <div class="card border border-purple">
                  <div class="card-body">
                    <h5 class="card-title">
                      Introductory Post
                    </h5>
                    <div>This is my introductory post.</div>
                    <button class="btn btn-success" role="button">Edit Post</button>
                    <button class="btn btn-danger" role="button">Delete Post</button>

                  </div>
                </div>

                </br>
                <!-- <a class="text-purple" href="create_post.html">Create post</a> -->
                <a class="btn btn-purple btn-lg" href="create_post.php" role="button">Create Post</a>
              </div>
            </div>
          </div>
        </div>

        <br />
        <div id="footer"></div>
      </div>
    </div>
  <?php
    // if user has not logged in, redirect to welcome page
  } else {
    header("Location: ../auth/welcome.php");
  }
  ?>

  <script src="../layout/layout.js"></script>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

  <script>
    function displayProfileInfo() {
      var displayMsg = "";
      if (1 == 1) {
        displayMsg = "1";
      } else displayMsg = "0"
    }
  </script>

</body>

</html>