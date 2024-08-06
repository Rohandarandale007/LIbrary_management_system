<?php
$servername = "localhost";
$username = "root";
$password = "Rohan@1234";
$database = "library";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create a table for books if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    publication_year INT,
    ISBN VARCHAR(13) NOT NULL
)";
if ($conn->query($sql) === FALSE) {
    echo "Error creating table: " . $conn->error;
}

// CREATE - Add a new book
if (isset($_POST['add_book'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = $_POST['year'];
    $isbn = $_POST['isbn'];

    $sql = "INSERT INTO books (title, author, publication_year, ISBN) VALUES ('$title', '$author', $year, '$isbn')";
    if ($conn->query($sql) === TRUE) {
        echo "New book added successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// READ - List all books
$sql = "SELECT * FROM books";
$result = $conn->query($sql);

// UPDATE - Edit book information
if (isset($_POST['edit_book'])) {
    $id = $_POST['book_id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = $_POST['year'];
    $isbn = $_POST['isbn'];

    $sql = "UPDATE books SET title='$title', author='$author', publication_year=$year, ISBN='$isbn' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "Book information updated successfully.";
    } else {
        echo "Error updating book information: " . $conn->error;
    }
}

// DELETE - Remove a book
if (isset($_POST['delete_book'])) {
    $id = $_POST['book_id'];

    $sql = "DELETE FROM books WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "Book deleted successfully.";
    } else {
        echo "Error deleting book: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Library Management System</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Library Management System</a>
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
          <a class="nav-link" href="#">Contact</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Services</a>
        </li>
        
    </ul>
       
    </div>
</div>
<form class="d-flex" role="search">
  <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
  <button class="btn btn-outline-success" type="submit">Search</button>
</form>
</nav>
    <div class="container mt-5">
        <h1 class="text-center">Library Management System</h1>

        <!-- Add a book -->
        <h2>Add a New Book</h2>
        <form method="POST" class="mb-4">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" name="title" required>
            </div>
            <div class="form-group">
                <label for="author">Author</label>
                <input type="text" class="form-control" name="author" required>
            </div>
            <div class="form-group">
                <label for="year">Publication Year</label>
                <input type="number" class="form-control" name="year">
            </div>
            <div class="form-group">
                <label for="isbn">ISBN</label>
                <input type="text" class="form-control" name="isbn" required>
            </div>
            <button type="submit" class="btn btn-primary" name="add_book">Add Book</button>
            <a class="btn btn-primary" href="issue_book.php" role="button">Issue Book</a>
        </form>

        <!-- List all books -->
        <h2>Books in the Library</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Publication Year</th>
                    <th>ISBN</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['title'] . "</td>";
                        echo "<td>" . $row['author'] . "</td>";
                        echo "<td>" . $row['publication_year'] . "</td>";
                        echo "<td>" . $row['ISBN'] . "</td>";
                        echo "<td>";
                        echo "<a href='#' class='btn btn-info btn-sm' data-toggle='modal' data-target='#editModal{$row['id']}'>Edit</a> ";
                        echo "<a href='#' class='btn btn-danger btn-sm' data-toggle='modal' data-target='#deleteModal{$row['id']}'>Delete</a>";
                        echo "</td>";
                        echo "</tr>";
                ?>
                <!-- Edit Modal -->
                <div class="modal fade" id="editModal<?=$row['id']?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Edit Book</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!-- Edit book form -->
                                <form method="POST">
                                    <div class="form-group">
                                        <label for="title">Title</label>
                                        <input type="text" class="form-control" name="title" value="<?=$row['title']?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="author">Author</label>
                                        <input type="text" class="form-control" name="author" value="<?=$row['author']?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="year">Publication Year</label>
                                        <input type="number" class="form-control" name="year" value="<?=$row['publication_year']?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="isbn">ISBN</label>
                                        <input type="text" class="form-control" name="isbn" value="<?=$row['ISBN']?>">
                                    </div>
                                    <input type="hidden" name="book_id" value="<?=$row['id']?>">
                                    <button type="submit" class="btn btn-info" name="edit_book">Update Book</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Delete Modal -->
                <div class="modal fade" id="deleteModal<?=$row['id']?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteModalLabel">Delete Book</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete this book?</p>
                                <form method="POST">
                                    <input type="hidden" name="book_id" value="<?=$row['id']?>">
                                    <button type="submit" class="btn btn-danger" name="delete_book">Delete Book</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='5'>No books found in the library.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
