<?php 
include('db.php');

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";

// Function to sanitize input
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="styles.css"> 
</head>
<body>

<div class="container" style="max-width: 800px; margin: auto; background: white; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
    <a href="home.php" class="btn btn-secondary">Return to Home</a>
    <h1 class="h3 mb-4 text-gray-800">Profile</h1>

    <?php
    // Insert operation
    if (isset($_POST['save'])) {
        $profile_code  = sanitize_input($_POST['profile_code']);
        $profile_name  = sanitize_input($_POST['profile_name']);
        $profile_email = sanitize_input($_POST['profile_email']);
        $profile_number = sanitize_input($_POST['profile_number']);
        $profile_address = sanitize_input($_POST['profile_address']);

        $query = "INSERT INTO `Profile` (`profile_code`, `profile_name`, `profile_email`, `profile_number`, `profile_address`) 
                  VALUES ('$profile_code', '$profile_name', '$profile_email', '$profile_number', '$profile_address')";
        
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Data saved successfully');</script>";
            echo "<script>window.location='profile.php'</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
        }
    }

    // Update operation
    if (isset($_POST['edit'])) {
        $id = sanitize_input($_POST['id']);
        $profile_code  = sanitize_input($_POST['profile_code']);
        $profile_name  = sanitize_input($_POST['profile_name']);
        $profile_email = sanitize_input($_POST['profile_email']);
        $profile_number = sanitize_input($_POST['profile_number']);
        $profile_address = sanitize_input($_POST['profile_address']);

        $query = "UPDATE `Profile` SET `profile_code`='$profile_code', `profile_name`='$profile_name', 
                  `profile_email`='$profile_email', `profile_number`='$profile_number', `profile_address`='$profile_address' 
                  WHERE id='$id'";

        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Data updated successfully');</script>";
            echo "<script>window.location='profile.php'</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
        }
    }

    // Delete operation
    if ($action == 'delete' && isset($_GET['id'])) {
        $id = sanitize_input($_GET['id']);
        $query = "DELETE FROM `Profile` WHERE id='$id'";
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Data deleted successfully');</script>";
            echo "<script>window.location='profile.php'</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
        }
    }
    ?>

    <?php if ($action == 'edit' || $action == 'add') { 
        $id = isset($_GET['id']) ? sanitize_input($_GET['id']) : 0;
        $data = ["profile_code" => "", "profile_name" => "", "profile_email" => "", "profile_number" => "", "profile_address" => ""];

        if ($action == 'edit') {
            $query = "SELECT * FROM Profile WHERE id='$id'";
            $result = mysqli_query($conn, $query);
            if ($result && mysqli_num_rows($result) > 0) {
                $data = mysqli_fetch_assoc($result);
            }
        }
    ?>
        <form method="post">
            <input type="hidden" name="id" value="<?= $id ?>">
            <table class="table table-striped table-bordered">
                <tr>
                    <td align="right"><b>Profile Code</b></td>
                    <td><input type="text" name="profile_code" class="form-control" value="<?= $data['profile_code'] ?>" required></td>
                </tr>
                <tr>
                    <td align="right"><b>Name</b></td>
                    <td><input type="text" name="profile_name" class="form-control" value="<?= $data['profile_name'] ?>" required></td>
                </tr>
                <tr>
                    <td align="right"><b>Email</b></td>
                    <td><input type="email" name="profile_email" class="form-control" value="<?= $data['profile_email'] ?>" required></td>
                </tr>
                <tr>
                    <td align="right"><b>Mobile Number</b></td>
                    <td><input type="text" name="profile_number" class="form-control" value="<?= $data['profile_number'] ?>" required></td>
                </tr>
                <tr>
                    <td align="right"><b>Address</b></td>
                    <td><input type="text" name="profile_address" class="form-control" value="<?= $data['profile_address'] ?>" required></td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <button type="submit" class="btn btn-success" name="<?= $action == 'edit' ? 'edit' : 'save' ?>">Save</button>
                    </td>
                </tr>
            </table>
        </form>
    <?php } else { ?>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="profile.php?action=add&id=0" class="btn btn-primary">Insert</a>
            <input type="text" id="search" class="form-control w-25" placeholder="Search...">
        </div>

        <table id="profileTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Profile Code</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile Number</th>
                    <th>Address</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $query = "SELECT * FROM Profile ORDER BY id DESC";
            $result = mysqli_query($conn, $query);
            while ($data = mysqli_fetch_assoc($result)) {
            ?>
                <tr>
                    <td><?= $data["id"] ?></td>
                    <td><?= $data["profile_code"] ?></td>
                    <td><?= $data["profile_name"] ?></td>
                    <td><?= $data["profile_email"] ?></td>
                    <td><?= $data["profile_number"] ?></td>
                    <td><?= $data["profile_address"] ?></td>
                    <td>
                        <a href="profile.php?action=edit&id=<?= $data["id"] ?>" class="btn btn-success">Edit</a>
                        <a href="profile.php?action=delete&id=<?= $data["id"] ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    <?php } ?>
</div>

<script>
document.getElementById('search').addEventListener('keyup', function() {
    let value = this.value.toLowerCase();
    document.querySelectorAll('#profileTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(value) ? '' : 'none';
    });
});
</script>

</body>
</html>
