<!DOCTYPE html>
<html>

<head>
    <title>Employee Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h1 {
            text-align: center;
        }

        form {
            margin: 20px;
            max-width: 400px;
            border: 1px solid #ccc;
            padding: 20px;
            background-color: #f9f9f9;
        }

        input[type="text"],
        input[type="date"],
        input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // Form validation
            $("form").on("submit", function (event) {
                var isValid = true;

                // Reset error messages
                $(".error").text("");

                // Validate name field
                var name = $("#name").val();
                if (name === "") {
                    $("#nameError").text("Name is required");
                    isValid = false;
                }
                // Validate birthdate field
                var birthdate = $("#birthdate").val();
                if (birthdate === "") {
                    $("#birthdateError").text("Birthdate is required");
                    isValid = false;
                }

                // Validate phone number field
                var phonenumber = $("#phonenumber").val();
                if (phonenumber === "") {
                    $("#phonenumberError").text("Phone Number is required");
                    isValid = false;
                } else {
                    var phonePattern = /^[0-9]{10}$/;
                    if (!phonenumber.match(phonePattern)) {
                        $("#phonenumberError").text("Please enter a valid 10-digit phone number");
                        isValid = false;
                    }
                }

                // Validate email field
                var email = $("#email").val();
                if (email === "") {
                    $("#emailError").text("Email is required");
                    isValid = false;
                } else {
                    var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                    if (!email.match(emailPattern)) {
                        $("#emailError").text("Please enter a valid email address");
                        isValid = false;
                    }
                }

                // Validate mandatory fields
                $(".mandatory").each(function () {
                    if ($(this).val() === "") {
                        var fieldName = $(this).attr("placeholder");
                        $("#mandatoryError").text(fieldName + " is required");
                        isValid = false;
                    }
                });

                if (!isValid) {
                    event.preventDefault();
                }
            });
        });
    </script>
</head>

<center><body>
    <h1>Employee Management System</h1>
    <h2>Add Employee</h2>
    <form method="post">
        <input type="hidden" name="employee_id" placeholder="Employee ID" value="<?php echo isset($_POST['employee_id']) ? $_POST['employee_id'] : ''; ?>" >
        <input type="text" name="employee_name" placeholder="Employee Name" value="<?php echo isset($_POST['employee_name']) ? $_POST['employee_name'] : ''; ?>" required><br><br>
        <input type="date" name="employee_birthdate" placeholder="Employee Birthdate" value="<?php echo isset($_POST['employee_birthdate']) ? $_POST['employee_birthdate'] : ''; ?>" required><br><br>
        <input type="text" name="employee_phonenumber" placeholder="Employee Phone Number" value="<?php echo isset($_POST['employee_phonenumber']) ? $_POST['employee_phonenumber'] : ''; ?>" required><br><br>
        <input type="email" name="employee_emailaddress" placeholder="Employee Email Address" value="<?php echo isset($_POST['employee_emailaddress']) ? $_POST['employee_emailaddress'] : ''; ?>" required><br><br>
        <input type="submit" name="add_employee" value="Add Employee">
    </form>

    <?php
    // Database connection
    $conn = mysqli_connect("localhost", "root", "dharmi", "test");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Add employee
    if (isset($_POST['add_employee'])) {
        $employee_id = $_POST['employee_id'];
$employee_name = $_POST['employee_name'];
$employee_birthdate = $_POST['employee_birthdate'];
$employee_phonenumber = $_POST['employee_phonenumber'];
$employee_emailaddress = $_POST['employee_emailaddress'];

$employee_birthdate = date('Y-m-d', strtotime($employee_birthdate));
$sql = "INSERT INTO employee ( employee_name, employee_birthdate, employee_phonenumber, employee_emailaddress) VALUES ( '$employee_name', '$employee_birthdate', '$employee_phonenumber', '$employee_emailaddress')";

if (mysqli_query($conn, $sql)) {
    echo "Employee added successfully!";
} else {
    echo "Error adding employee: " . mysqli_error($conn);
}

    }

    // Update employee
    if (isset($_POST['update_employee'])) {
        $employee_id = $_POST['employee_id'];
        $employee_name = $_POST['employee_name'];
        $employee_birthdate = $_POST['employee_birthdate'];
        $employee_phonenumber = $_POST['employee_phonenumber'];
        $employee_emailaddress = $_POST['employee_emailaddress'];

        $sql = "UPDATE employee SET employee_name='$employee_name', employee_birthdate='$employee_birthdate', employee_phonenumber='$employee_phonenumber', employee_emailaddress='$employee_emailaddress' WHERE employee_id='$employee_id'";

        if (mysqli_query($conn, $sql)) {
            echo "Employee updated successfully!";
        } else {
            echo "Error updating employee: " . mysqli_error($conn);
        }
    }

    // Delete employee
    if (isset($_GET['delete_employee'])) {
        $employee_id = $_GET['delete_employee'];

        $sql = "DELETE FROM employee WHERE employee_id='$employee_id'";

        if (mysqli_query($conn, $sql)) {
            echo "Employee deleted successfully!";
            } else {
            echo "Error deleting employee: " . mysqli_error($conn);
            }
            }
            // View employees
echo "<h2>View Employees</h2>";
$sql = "SELECT * FROM employee";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Birthdate</th>
                <th>Phone Number</th>
                <th>Email Address</th>
                <th>Action</th>
            </tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>" . $row['employee_id'] . "</td>
                <td>" . $row['employee_name'] . "</td>
                <td>" . $row['employee_birthdate'] . "</td>
                <td>" . $row['employee_phonenumber'] . "</td>
                <td>" . $row['employee_emailaddress'] . "</td>
                <td>
                    <a href='?edit_employee=" . $row['employee_id'] . "'>Edit</a>
                    <a href='?delete_employee=" . $row['employee_id'] . "'>Delete</a>
                </td>
            </tr>";
    }

    echo "</table>";
} else {
    echo "No employees found.";
}

// Edit employee
if (isset($_GET['edit_employee'])) {
    $employee_id = $_GET['edit_employee'];

    $sql = "SELECT * FROM employee WHERE employee_id='$employee_id'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        echo "<h2>Edit Employee</h2>";
        echo "<form method='post'>
                <input type='hidden' name='employee_id' value='" . $row['employee_id'] . "' required readonly><br><br>
                <input type='text' name='employee_name' value='" . $row['employee_name'] . "' required><br><br>
                <input type='date' name='employee_birthdate' value='" . $row['employee_birthdate'] . "' required><br><br>
                <input type='text' name='employee_phonenumber' value='" . $row['employee_phonenumber'] . "' required><br><br>
                <input type='email' name='employee_emailaddress' value='" . $row['employee_emailaddress'] . "' required><br><br>
                <input type='submit' name='update_employee' value='Update Employee'>
              </form>";
    } else {
        echo "Employee not found.";
    }
}

// Close database connection
mysqli_close($conn);
?>
</body></center>
</html>