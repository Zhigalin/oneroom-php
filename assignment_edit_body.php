<?php
  // Process POST data if form was submitted
  if (isset($_POST['submit'])) {
    // grab assignment and course ids
    $assignment_id = $_SESSION['assignment_id'];
    $course_id = $_SESSION['course_id'];
    
    // Grab the profile data from the POST
    $name = mysqli_real_escape_string($dbc, trim($_POST['name']));
    $due_date = mysqli_real_escape_string($dbc, trim($_POST['due_date']));
  
    // REMEMBER TO DO VALIDATION ON DATE
    // ACCEPT ONLY THE MM/DD/YEAR FORMAT
   
    $matches = array();
    if (!empty($name) && !empty($due_date) &&
        preg_match('/(^\d{1,2})\/(\d{1,2})\/(\d{4})$/', $due_date, $matches)) {
      // Convert due date to MySQL's YYYY-MM-DD format
      $due_date = $matches[3] . '-' .
                  $matches[1] . '-' .
                  $matches[2];
                  
      // Insert new row into 'assignments' table
      $query = "UPDATE assignments SET
                name = '$name', due_date = '$due_date'
                WHERE
                assignment_id = '$assignment_id'";
      
      $result = mysqli_query($dbc, $query)
                  or die('Error querying database: ' . mysqli_error($dbc));
  
      // Redirect to success page
      $_SESSION['assignment_id'] = $assignment_id;
      $_SESSION['course_id'] = $course_id;
      redirect('assignment_edit_success.php');
    } else {
      echo '<p class="error">You must enter all of the sign-up data, including the desired password twice.</p>';
    }
  } else {
    // Otherwise, get existing assignment name and due date from the database
    $assignment_id = $_GET['assignment_id'];
    $query = "SELECT name, due_date, course_id FROM assignments WHERE
              assignment_id = '$assignment_id'";
    $result = mysqli_query($dbc, $query)
              or die('Error querying database: ' . mysqli_error($dbc));
    if (mysqli_num_rows($result) == 1) {
      // Success if only one row is returned
      $row = mysqli_fetch_array($result);
    } else {
        // Something went wrong if the number of rows returned is not 1
      die('Error querying database:
           no assignment with this id or more than one assignment with the same id.');
    }
    $name = $row['name'];
    $due_date_array = explode('-', $row['due_date']);
    $due_date = $due_date_array[1] . '/' . $due_date_array[2] . '/' .
                $due_date_array[0];
    $course_id = $row['course_id'];
    
    $_SESSION['assignment_id'] = $assignment_id;
    $_SESSION['course_id'] = $course_id;
  }
?>

<!-- Course Edit Form -->
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
  <!-- Assignment Name -->
  <label for="name">assignment name:</label>
  <input type="text" id="name" name="name"
         value="<?php echo $name; ?>" /><br />
         
  <!-- Assignment Due Date -->
  <label for="due_date">due date:</label>
  <input type="text" id="due_date" name="due_date"
         value="<?php echo $due_date; ?>" /><br />
  
  <!-- Submission -->
  <input type="submit" value="update assignment" id="submit" name="submit" />
</form>
