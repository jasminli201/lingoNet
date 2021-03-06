<?php
require("../db/connectdb.php");

// get post feed information for all users 
function getPostfeedInfo()
{
  global $db;
  $user_info_array = array();
  $query = "SELECT * FROM users, native, target WHERE users.email = native.email AND users.email = target.email";
  $result = mysqli_query($db, $query);

  if (mysqli_num_rows($result) > 0) {
    while ($row = $result->fetch_assoc()) {
      $user = array(
        'firstName' => $row['firstName'],
        'lastName' => $row['lastName'],
        'email' => $row['email'],
        'target' => $row['target'],
        'native' => $row['native']
      );

      // do not display info for current user
      if ($user['email'] != $_SESSION['email']) {
        $user_json = json_encode($user);
        array_push($user_info_array, $user_json);
      }
    }
  }
  // mysqli_free_result($query);
  return $user_info_array;
}

// checks if user is already in friend table
function inFriendtable($email, $friendEmail)
{
  global $db;
  $query = "SELECT * FROM friend WHERE email = ? AND friendEmail = ?";
  $check_stmt = $db->prepare($query);
  $check_stmt->bind_param("ss", $email, $friendEmail);
  $check_stmt->execute();
  $check_stmt->store_result();

  if ($check_stmt->num_rows() == 1) {
    return true;
  } else {
    return false;
  }
}

// checks if user is already a pending friend
function isPendingFriend($email, $friendEmail)
{
  global $db;
  $query = "SELECT * FROM pending WHERE email = ? AND friendEmail = ?";
  $check_stmt = $db->prepare($query);
  $check_stmt->bind_param("ss", $email, $friendEmail);
  $check_stmt->execute();
  $check_stmt->store_result();

  if ($check_stmt->num_rows() == 1) {
    return true;
  } else {
    return false;
  }
}

// checks if user is already an accepted friend
function isAcceptedFriend($email, $friendEmail)
{
  global $db;
  $query = "SELECT * FROM friend WHERE (email = ? AND friendEmail = ?) OR (email = ? AND friendEmail = ?)";
  $check_stmt = $db->prepare($query);
  $check_stmt->bind_param("ssss", $email, $friendEmail, $friendEmail, $email);
  $check_stmt->execute();
  $check_stmt->store_result();

  if ($check_stmt->num_rows() == 1) {
    return true;
  } else {
    return false;
  }
}

function addFriendtoPending($email, $friendEmail)
{
  global $db;

  // add friend to pending
  $stmt = $db->prepare("INSERT INTO pending(email, friendEmail) VALUES (?, ?)");
  $stmt->bind_param("ss", $email, $friendEmail);
  if (!$stmt->execute()) {
    return "Error adding friend";
  } else {
    header("Location: ../pages/postfeed.php");
  }
  $stmt->close();
}

function getMoreInfo($email)
{
  global $db;
  $query = "SELECT * FROM post, users WHERE post.email =  '" . $email . "' AND post.email = users.email";

  $more_info = array();
  $result = mysqli_query($db, $query);
  if (mysqli_num_rows($result) > 0) {
    while ($row = $result->fetch_assoc()) {
      $more_info = array(
        'firstName' => $row['firstName'],
        'lastName' => $row['lastName'],
        'email' => $row['email'],
        'introduction' => $row['introduction'],
        'lookingFor' => $row['lookingFor'],
        'whyYou' => $row['whyYou']
      );
    }
  }
  // mysqli_free_result($query);
  return $more_info;
}
