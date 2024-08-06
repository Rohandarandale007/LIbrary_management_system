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

// CREATE - Issue a book
if (isset($_POST['issue_book'])) {
    $isbn = $_POST['isbn'];
    $book_name = $_POST['book_name'];
    $student_name = $_POST['student_name'];
    $issue_date = $_POST['issue_date'];
    $return_date = $_POST['return_date'];

    $sql = "INSERT INTO issued_books (isbn, book_name, student_name, issue_date, return_date) 
            VALUES ('$isbn', '$book_name', '$student_name', '$issue_date', '$return_date')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Book issued successfully.";
    } else {
        echo "Error issuing book: " . $conn->error;
    }
}

// READ - List all issued books
$sql_issued_books = "SELECT * FROM issued_books";
$result_issued_books = $conn->query($sql_issued_books);

// UPDATE - Edit issued book information
if (isset($_POST['edit_issued_book'])) {
    $id = $_POST['issued_book_id'];
    $isbn = $_POST['isbn'];
    $book_name = $_POST['book_name'];
    $student_name = $_POST['student_name'];
    $issue_date = $_POST['issue_date'];
    $return_date = $_POST['return_date'];

    $sql = "UPDATE issued_books SET isbn='$isbn', book_name='$book_name', student_name='$student_name', issue_date='$issue_date', return_date='$return_date' WHERE id=$id";
    
    if ($conn->query($sql) === TRUE) {
        echo "Issued book information updated successfully.";
    } else {
        echo "Error updating issued book information: " . $conn->error;
    }
}

// DELETE - Remove an issued book
if (isset($_POST['delete_issued_book'])) {
    $id = $_POST['issued_book_id'];

    $sql = "DELETE FROM issued_books WHERE id=$id";
    
    if ($conn->query($sql) === TRUE) {
        echo "Issued book deleted successfully.";
    } else {
        echo "Error deleting issued book: " . $conn->error;
    }
}

// Fetch issued book data for editing
if (isset($_POST['get_issued_book_data'])) {
    $issued_book_id = $_POST['issued_book_id'];

    $sql = "SELECT * FROM issued_books WHERE id=$issued_book_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode(array());
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
    <div class="container mt-5">
        <!-- Issue a Book -->
        <h2>Issue a Book</h2>
        <form method="POST" class="mb-4">
            <!-- Include form fields for issuing a book (ISBN, book_name, student_name, issue_date, return_date) -->
            <div class="form-group">
                <label for="isbn">ISBN</label>
                <input type="text" class="form-control" name="isbn" required>
            </div>
            <div class="form-group">
                <label for="book_name">Book Name</label>
                <input type="text" class="form-control" name="book_name" required>
            </div>
            <div class="form-group">
                <label for="student_name">Student Name</label>
                <input type="text" class="form-control" name="student_name" required>
            </div>
            <div class="form-group">
                <label for="issue_date">Issue Date</label>
                <input type="date" class="form-control" name="issue_date" required>
            </div>
            <div class="form-group">
                <label for="return_date">Return Date</label>
                <input type="date" class="form-control" name="return_date" required>
            </div>
            <button type="submit" class="btn btn-primary" name="issue_book">Issue Book</button>
        </form>

        <!-- List of Issued Books -->
        <h2>Issued Books</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ISBN</th>
                    <th>Book Name</th>
                    <th>Student Name</th>
                    <th>Issue Date</th>
                    <th>Return Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_issued_books->num_rows > 0) {
                    while ($row = $result_issued_books->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['isbn'] . "</td>";
                        echo "<td>" . $row['book_name'] . "</td>";
                        echo "<td>" . $row['student_name'] . "</td>";
                        echo "<td>" . $row['issue_date'] . "</td>";
                        echo "<td>" . $row['return_date'] . "</td>";
                        echo "<td>";
                        echo "<a href='#' class='btn btn-info btn-sm edit-issued-book' data-toggle='modal' data-target='#editModal' data-id='{$row['id']}'>Edit</a> ";
                        echo "<a href='#' class='btn btn-danger btn-sm delete-issued-book' data-toggle='modal' data-target='#deleteModal' data-id='{$row['id']}'>Delete</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No books issued.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Issued Book</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <!-- Include form fields for editing issued book details (ISBN, book_name, student_name, issue_date, return_date) -->
                        <div class="form-group">
                            <label for="isbn">ISBN</label>
                            <input type="text" class="form-control" name="isbn" required>
                        </div>
                        <div class="form-group">
                            <label for="book_name">Book Name</label>
                            <input type="text" class="form-control" name="book_name" required>
                        </div>
                        <div class="form-group">
                            <label for="student_name">Student Name</label>
                            <input type="text" class="form-control" name="student_name" required>
                        </div>
                        <div class="form-group">
                            <label for="issue_date">Issue Date</label>
                            <input type="date" class="form-control" name="issue_date" required>
                        </div>
                        <div class="form-group">
                            <label for="return_date">Return Date</label>
                            <input type="date" class="form-control" name="return_date" required>
                        </div>
                        <input type="hidden" name="issued_book_id" value="">
                        <button type="submit" class="btn btn-primary" name="edit_issued_book">Update Book</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Issued Book</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this issued book?</p>
                    <form method="POST">
                        <input type="hidden" name="issued_book_id" value="">
                        <button type="submit" class="btn btn-danger" name="delete_issued_book">Delete Book</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // JavaScript to set the issued_book_id in modals and prepopulate the form when Edit button is clicked
        $('.edit-issued-book').click(function() {
            var issued_book_id = $(this).data('id');
            var modal = $('#editModal');
            modal.find('input[name="issued_book_id"]').val(issued_book_id);

            // Fetch the existing data for the selected issued book using an AJAX request
            $.ajax({
                url: 'get_issued_book.php',
                method: 'POST',
                data: { issued_book_id: issued_book_id },
                success: function(data) {
                    var issuedBookData = JSON.parse(data);
                    modal.find('input[name="isbn"]').val(issuedBookData.isbn);
                    modal.find('input[name="book_name"]').val(issuedBookData.book_name);
                    modal.find('input[name="student_name"]').val(issuedBookData.student_name);
                    modal.find('input[name="issue_date"]').val(issuedBookData.issue_date);
                    modal.find('input[name="return_date"]').val(issuedBookData.return_date);
                }
            });
        });

        // JavaScript to set the issued_book_id in the delete modal when Delete button is clicked
        $('.delete-issued-book').click(function() {
            var issued_book_id = $(this).data('id');
            $('#deleteModal').find('input[name="issued_book_id"]').val(issued_book_id);
        });
    </script>
</body>
</html>
