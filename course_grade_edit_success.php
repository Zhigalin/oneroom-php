<?php
  /* course_grade_edit_success.php
   * -----------------------------
   * course_grade_edit_success.php is run when a course grade has been
   * successfully edited by a teacher.  A success message is displayed.
   */
  
  // PHP initialization (utility functions, session start, etc.)
  require_once('init_page.php');

  $course_id = $_SESSION['course_id'];
  
  // Permissions: only teachers of the course can edit a student's course
  // grade.
  if (!$logged_in) {
    redirect('nopermissions.php');
  } else {
    if ($is_teacher) {
      // if logged in user is a teacher, ok if she's teaching the course
  
      // is the user teaching the class?
      $query = "SELECT * FROM courses_teachers WHERE
                course_id = '$course_id' and teacher_id = '$user_id'";
      $result = mysqli_query($dbc, $query) or redirect('500.php');
      // if not, redirect
      if (mysqli_num_rows($result) == 0) {
        redirect('nopermissions.php');
      }
    } else {
      // students cannot edit courses
      redirect('nopermissions.php');
    }
  }

  // If we get here without being redirected, we're ok; go on with script

  $student_id = $_SESSION['student_id'];
  
  // Get course's full name
  $course_full_name = get_course_full_name($dbc, $course_id);  
  $course_name = $course_full_name['name'];
  $semester = $course_full_name['semester'];
  $year = $course_full_name['year'];
  
  // Get student name
  $student_name = get_user_full_name($dbc, $student_id);
  $first_name = $student_name['first_name'];
  $last_name = $student_name['last_name'];
  
  // Set page title
  $page_title = 'Course Grade Input Successful';
  
  // Display header
  require_once('oneroom_header.php');
?>

<!-- Content -->
<div id="content">
  <!-- Content heading -->
  <div id="content-heading">
    <h1>Course Grade Successfully Inputted</h1>
  </div>
        
  <!-- Content body -->
  <div id="content-body">
    <?php
      // Print success message
      echo '<p>You have successfully inputted a course grade for ';
      echo "<a href=\"grades_page.php?course_id=$course_id&";
      echo "student_id=$student_id\">";
      echo "$first_name $last_name </a>";
      echo "in <a href=\"course_page.php?course_id=$course_id\">";
      echo "$course_name, $semester $year</a>.</p>";
    ?>
  </div>
</div>

<?php
  // Display footer
  require_once('oneroom_footer.php');
  
  // PHP end-script (close MySQL connection)
  require_once('close_page.php');
?>

