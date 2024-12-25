<?php
// INSERT INTO `tnexa` (`sno`, `title`, `description`, `tstamp`) VALUES (NULL, 'Eat food', 'Roshan please eat the food that I\'ve left you on the fridge.', current_timestamp());
$insert = false;
// Connnect to the Database
$servername = "localhost";
$username = "root";
$password = "";
$database = "dnexa";

// Create a connection
$conn = mysqli_connect($servername, $username, $password, $database);
// Die if connection was not successful
if(!$conn){
  die("Sorry we failed to connect: " . mysqli_connect_error());
}
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
  if(isset($_POST['snoEdit'])){
  // Update the recored
  $sno = $_POST['snoEdit'];
  $title = $_POST['titleEdit'];
  $description = $_POST['descriptionEdit'];
  $sql = "UPDATE `tnexa` SET `title` = '$title' , `description` = '$description' WHERE `tnexa`.`sno` = $sno;";
  $result = mysqli_query($conn, $sql);
  if($result){
    echo "We updated the record";
  }
  else{
    echo "We could not update the record.";
  }
  }
  else{
  $title = $_POST['title'];
  $description = $_POST['description'];
  //Sql query to be executed
  $sql = "INSERT INTO `tnexa` (`title`, `description`) VALUES ('$title', '$description')";
  $result = mysqli_query($conn, $sql);
  if($result){
    $insert = true;
  }
  else{
    echo "The note was not added successfully because of this error --->" . mysqli_error($conn);
  }
}
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nexa - Note taking reimagined</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <!-- Edit modal -->
<!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">
  Edit Modal
</button> -->

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editModalLabel">Edit this note</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action ="/crud/index.php" method="post">
          <input type="hidden" name="snoEdit" id="snoEdit">
          <div class="mb-3">
            <label for="title" class="form-label">Note Title</label>
            <input type="text" class="form-control" id="titleEdit" name="titleEdit" aria-describedby="emailHelp">
            </div>
          <div>
              <label for="floatingTextarea2">Note Description</label>
              <textarea class="form-control" placeholder="Leave a description" id="descriptionEdit" name="descriptionEdit" rows="3"></textarea>
            </div>
            <div class="mb-3"></div>
            <button type="submit" class="btn btn-primary">Add Note</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
          <a class="navbar-brand" href="#">Nexa</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">About</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Contact Us</a>
              </li>
            </ul>
            <form class="d-flex" role="search">
              <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
              <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
          </div>
        </div>
      </nav>
      <?php
      if($insert){
        echo"<div class='alert alert-warning alert-dismissible fade show' role='alert'>
        <strong>SUCCESS!</strong> Your note has been added successfully.
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
      }
      ?>
      <div class="container my-4">
        <h2>Add a note</h2>
        <form action ="/crud/index.php" method="post">
            <div class="mb-3">
              <label for="title" class="form-label">Note Title</label>
              <input type="text" class="form-control" id="title" name="title" aria-describedby="emailHelp">
              </div>
            <div>
                <label for="floatingTextarea2">Note Description</label>
                <textarea class="form-control" placeholder="Leave a description" id="description" name="description" rows="3"></textarea>
              </div>
              <div class="mb-3"></div>
              <button type="submit" class="btn btn-primary">Update Note</button>
          </form>
      </div>
      <div class="container my-4">
        <table class="display" id="myTable">
  <thead>
    <tr>
      <th scope="col">S.No</th>
      <th scope="col">Title</th>
      <th scope="col">Description</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
  <?php 
        $sql = "SELECT * FROM `tnexa`";
        $result = mysqli_query($conn, $sql);
        $sno = 0;
        while($row = mysqli_fetch_assoc($result)){
          $sno = $sno + 1;
          echo " <tr>
      <th scope='row'>". $sno . "</th>
      <td>". $row['title'] . "</td>
      <td>". $row['description'] . "</td>
      <td> <button class='btn btn-sm btn-primary edit' id=".$row['sno'].">Edit</button> <a href='/del'>Delete</a></td>
    </tr>";
        }
        ?>
  </tbody>
  
</table>  
      </div>
      <hr>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

  <script>
    $(document).ready(function () {
      $('#myTable').DataTable();
    });
  </script>
  <script>
    // Select all elements with the class 'edit'
    const edits = document.getElementsByClassName('edit');
  
    // Convert the HTMLCollection to an array and loop through each element
    Array.from(edits).forEach((element) => {
      // Add an event listener for the 'click' event
      element.addEventListener("click", (e) => {
        console.log("edit",);
        tr =  e.target.parentNode.parentNode;
        title = tr.getElementsByTagName('td')[0].innerText;
        description = tr.getElementsByTagName('td')[1].innerText;
        console.log(title, description);
        titleEdit.value = title;
        descriptionEdit.value = description;
        snoEdit.value = e.target.id;
        console.log(e.target.id)
        $('#editModal').modal('toggle');
      })
    });
  </script>
  
  </body>
</html>