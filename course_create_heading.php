<?php
  // Only logged in teachers can edit courses
  if ($is_teacher) {
    echo '<h1>Create a New Course</h1>';
  } else {
  // Redirect to a page informing the user that he doesn't have the permissions.
  redirect('nopermissions.php');
  }
?>