<!-- ***************************************************************************************/
* Title: Bootstrap source code
* Author: Mark Otto, Jacob Thornton
* Date: 2021
* Code version: 4.6.0
* Availability: https://getbootstrap.com/
***************************************************************************************/ -->
<?php
require("../sql/postfeed_sql.php");
require("../sql/profile_sql.php");
$user_info_array = getPostfeedInfo();
$arr_len = count($user_info_array);

if (isset($_POST['addFriend'])) {
  addFriendtoPending($_SESSION['email'], $_POST['friendEmail']);
}

if (isset($_GET['email'])) {
  $more_info = getMoreInfo($_GET['email']);
}

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Postfeed">
  <meta name="author" content="Jasmin Li, Rebecca Zhou">

  <title>Postfeed</title>
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
  <link rel="stylesheet" href="../css/layout.css" />
  <link rel="stylesheet" href="../css/content.css" />
  <link rel="stylesheet" href="../css/theme.css" />
</head>

<body>
  <?php
  // if user has not logged in, then redirect
  // otherwise, display content 
  if (!isset($_SESSION['loggedIn'])) {
    header("Location: ../auth/welcome.php");
  } else
  ?>

  <div class="page-container">
    <div class="content-wrap">
      <?php include("../layout/header.php") ?>

      <div class="position-relative overflow-hidden p-3 p-md-5 m-md-2">
        <div class="content">
          <div class="row">
            <div class="col-8">
              <h1 class="display-4">Postfeed</h1>
            </div>
            <div class="col-4" style="margin-top: 1rem;">
              <form id="search_form" method="post" onsubmit="getSearchResults()">
                <div class="input-group">
                  <input style="margin-right: 1em;" type="text" id="search_input" class="form-control" autofocus />
                  <span class="input-group-btn">
                    <button type="submit" class="form-control" value="Search">
                      <i class="fas fa-search" style="color: 6b359d"></i>
                    </button>
                  </span>
                </div>
              </form>
            </div>
          </div>
          <hr />
          <br />

          <div class="row">

            <?php
            $displayed_users = array();
            foreach ($user_info_array as $key => $value) {
              $language_info = getMultiLanguageInfo(json_decode($value)->email);
              if (!in_array($language_info, $displayed_users)) {
                array_push($displayed_users, $language_info); ?>
                <div class="col-md-4 align-items-stretch">
                  <div class="card border border-purple" style="margin-bottom: 2rem;">
                    <div class="card-header">
                      <div class="row">
                        <div class="col-10">
                          <h4>
                            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
                              <?= json_decode($value)->firstName ?>
                              <?= json_decode($value)->lastName ?>
                              <input type="hidden" name="firstName" id="firstName" value="<?= json_decode($value)->firstName ?>" />
                              <input type="hidden" name="lastName" id="lastName" value="<?= json_decode($value)->lastName ?>" />
                              <input type="hidden" name="friendEmail" id="friendEmail" value="<?= json_decode($value)->email ?>" />

                              <!-- if pending (outgoing), display right arrow icon -->
                              <?php if (isPendingFriend($_SESSION['email'], json_decode($value)->email)) : ?>
                                <!-- https://icons.getbootstrap.com/icons/arrow-right/ -->
                                <button type="button" disabled name="pending" class="btn btn-purple btn-sm" data-toggle="Disabled tooltip" data-placement="bottom" title="Request Sent">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z" />
                                  </svg>
                                </button>

                                <!-- if incoming, display left arrow icon -->
                              <?php elseif (isPendingFriend(json_decode($value)->email, $_SESSION['email'])) : ?>
                                <!-- https://icons.getbootstrap.com/icons/arrow-left/ -->
                                <button type="button" disabled name="incoming" class="btn btn-purple btn-sm" data-toggle="Disabled tooltip" data-placement="bottom" title="Incoming Friend Request">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
                                  </svg>
                                </button>

                                <!-- if in friend table, display accepted icon  -->
                              <?php elseif (isAcceptedFriend($_SESSION['email'], json_decode($value)->email)) : ?>
                                <!-- https://icons.getbootstrap.com/icons/person-check-fill/ -->
                                <button type="button" disabled name="accepted" class="btn btn-purple btn-sm" data-toggle="Disabled tooltip" data-placement="bottom" title="Accepted Friend">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-check-fill" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M15.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L12.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0z" />
                                    <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                                  </svg>
                                </button>

                                <!-- else if not in pending or friend table, then display addFriend button -->
                              <?php else : ?>
                                <!-- Add friend button -->
                                <button type="submit" name="addFriend" class="btn btn-purple btn-sm" data-toggle="tooltip" data-placement="bottom" title="Add Friend">
                                  <!-- https://icons.getbootstrap.com/icons/person-plus-fill/ -->
                                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-plus-fill" viewBox="0 0 16 16">
                                    <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                                    <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z" />
                                  </svg>
                                </button>
                              <?php endif; ?>
                            </form>
                          </h4>
                        </div>

                        <div class="col-2" style="text-align: right;">
                          <form action="" method="get">
                            <a href="<?php $_SERVER['PHP_SELF'] ?>?email=<?= json_decode($value)->email ?>">
                              <i class="fas fa-info-circle fa-lg" style="color: green"></i>
                            </a>

                            <input type="hidden" id="show_modal" value="<?php echo isset($_GET['email']); ?>" />

                            <!-- Modal -->
                            <div class="modal fade" id="moreInfo" tabindex="-1" role="dialog" aria-labelledby="moreInfoTitle" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">
                                      <?php if (sizeof($more_info) > 0) : ?>
                                        <?php echo $more_info['firstName'] . " " . $more_info['lastName']; ?>
                                      <?php else : ?>
                                        Currently no info :(
                                      <?php endif ?>
                                    </h5>

                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body">
                                    <table class="table">
                                      <tbody>
                                        <?php if (sizeof($more_info) > 0) : ?>
                                          <tr>
                                            <td><em>Introduction</em></td>
                                            <td><?php echo $more_info['introduction']; ?></td>
                                          </tr>
                                          <tr>
                                            <td><em>Looking For</em></td>
                                            <td><?php echo $more_info['lookingFor']; ?></td>
                                          </tr>
                                          <tr>
                                            <td><em>Why You?</em></td>
                                            <td><?php echo $more_info['whyYou']; ?></td>
                                          </tr>
                                        <?php else : ?>
                                          Come back later!
                                        <?php endif ?>
                                      </tbody>
                                    </table>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>

                    <div class="card-body">
                      <h6 class="card-subtitle mb-2">Can speak: </h6>

                      <?php foreach ($language_info['native'] as $native) echo $native . "<br/>"  ?>
                      <br />

                      <h6 class="card-subtitle mb-2 ">Wants to practice: </h6>

                      <?php foreach ($language_info['target'] as $target) echo $target . "<br />" ?>
                      <br />

                    </div>
                  </div>
                </div>
            <?php }
            } ?>
          </div>
        </div>
      </div>
    </div>

    <br />
    <?php include("../layout/footer.html") ?>
  </div>
  </div>

  <script src="../layout/layout.js"></script>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

  <script>
    function getSearchResults() {
      search_input = document.getElementById("search_input").value;
      alert("You entered: " + search_input);
      console.log(search_input);
    }

    // https://stackoverflow.com/questions/59312464/how-to-open-bootstrap-modal-by-using-a-php-get-request
    $(document).ready(function() {
      let show_modal = $('#show_modal').val();
      if (show_modal == 1) {
        $('#moreInfo').modal('show');
      }
    });
  </script>

</body>

</html>