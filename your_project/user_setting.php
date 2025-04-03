<?php 
include('db.php');

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";

// Function to sanitize input
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Fetch dropdown values
$profile_query = "SELECT id FROM Profile";
$profile_result = mysqli_query($conn, $profile_query);

$persona_query = "SELECT id FROM Persona";
$persona_result = mysqli_query($conn, $persona_query);
?>

<div class="container">
<a href="home.php" class="btn btn-secondary">Return to Home</a>
    <h1 class="h3 mb-4 text-gray-800">User Settings</h1>

    <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <a href="user_setting.php?action=add&id=0" class="btn btn-primary">Insert</a>
    </div><br>
    <input type="text" id="search" class="form-control w-25" placeholder="Search..." style="margin-left: auto;">
</div>

    <?php
    // Insert operation
    if (isset($_REQUEST['save'])) {
        $profile_id  = sanitize_input($_REQUEST['profile_id']);
        $persona_id  = sanitize_input($_REQUEST['persona_id']);
        $username  = sanitize_input($_REQUEST['username']);
        $password  = sanitize_input($_REQUEST['password']);
        $status  = sanitize_input($_REQUEST['status']);

        $query = "INSERT INTO `user_setting` (`Profile_id`, `Persona_id`, `username`, `password`, `Status`) 
                  VALUES ('$profile_id', '$persona_id', '$username', '$password', '$status')";
        
        $message = iud_data($query);
        echo "<script>alert('".($message == 'success' ? 'Data saved successfully' : 'Data not saved')."');</script>";
        echo "<script>window.location='user_setting.php'</script>";
    }

    // Update operation
    if (isset($_REQUEST['edit'])) {
        $id = $_REQUEST['id'];
        $profile_id  = sanitize_input($_REQUEST['profile_id']);
        $persona_id  = sanitize_input($_REQUEST['persona_id']);
        $username  = sanitize_input($_REQUEST['username']);
        $password  = sanitize_input($_REQUEST['password']);
        $status  = sanitize_input($_REQUEST['status']);

        $query = "UPDATE `user_setting` SET `Profile_id`='$profile_id', `Persona_id`='$persona_id', `username`='$username', 
                  `password`='$password', `Status`='$status' WHERE id='$id'";
        
        $message = iud_data($query);
        echo "<script>alert('".($message == 'success' ? 'Data updated successfully' : 'Failed to update')."');</script>";
        echo "<script>window.location='user_setting.php'</script>";
    }

    // Delete operation
    if ($action == 'delete') {
        $id = $_REQUEST['id'];
        $query = "DELETE FROM `user_setting` WHERE id='$id'";
        
        $message = iud_data($query);
        echo "<script>alert('".($message == 'success' ? 'Data deleted successfully' : 'Failed to delete')."');</script>";
        echo "<script>window.location='user_setting.php'</script>";
    }
    ?>

    <?php if ($action == 'edit' || $action == 'add') { 
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : null;
        $query = "SELECT * FROM user_setting WHERE id='$id'";
        $result = mysqli_query($conn, $query);
        $data = mysqli_fetch_assoc($result);
    ?>
        <form method="post" style="display: flex; flex-direction: column; align-items: center;">
            <table class="table table-bordered">
                <tr>
                    <td align="right"><b>Profile ID</b></td>
                    <td>
                        <select name="profile_id" class="form-control" required>
                            <option value="">Select Profile</option>
                            <?php while ($profile = mysqli_fetch_assoc($profile_result)) { ?>
                                <option value="<?= $profile['id']; ?>" <?= ($action == 'edit' && $data['Profile_id'] == $profile['id']) ? 'selected' : ''; ?>>
                                    <?= $profile['id']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align="right"><b>Persona ID</b></td>
                    <td>
                        <select name="persona_id" class="form-control" required>
                            <option value="">Select Persona</option>
                            <?php while ($persona = mysqli_fetch_assoc($persona_result)) { ?>
                                <option value="<?= $persona['id']; ?>" <?= ($action == 'edit' && $data['Persona_id'] == $persona['id']) ? 'selected' : ''; ?>>
                                    <?= $persona['id']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align="right"><b>Username</b></td>
                    <td><input type="text" name="username" class="form-control" value="<?= $action == 'edit' ? $data['username'] : '' ?>" required></td>
                </tr>
                <tr>
                    <td align="right"><b>Password</b></td>
                    <td><input type="password" name="password" class="form-control" value="<?= $action == 'edit' ? $data['password'] : '' ?>" required></td>
                </tr>
                <tr>
                    <td align="right"><b>Status</b></td>
                    <td>
                        <select name="status" class="form-control">
                            <option value="Active" <?= ($action == 'edit' && $data['Status'] == 'Active') ? 'selected' : '' ?>>Active</option>
                            <option value="Inactive" <?= ($action == 'edit' && $data['Status'] == 'Inactive') ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <button type="submit" class="btn btn-success" name="<?= $action == 'edit' ? 'edit' : 'save' ?>">Save</button>
                    </td>
                </tr>
            </table>
        </form>
    <?php } else { ?>
        <table id="userSettingTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Profile ID</th>
                    <th>Persona ID</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $query = "SELECT * FROM user_setting ORDER BY id DESC";
            $result = mysqli_query($conn, $query);
            while ($data = mysqli_fetch_assoc($result)) {
            ?>
                <tr>
                    <td><?= $data["id"] ?></td>
                    <td><?= $data["Profile_id"] ?></td>
                    <td><?= $data["Persona_id"] ?></td>
                    <td><?= $data["username"] ?></td>
                    <td><?= $data["password"] ?></td>
                    <td><?= $data["Status"] ?></td>
                    <td>
                        <a href="user_setting.php?action=edit&id=<?= $data["id"] ?>" class="btn btn-success">Edit</a>
                        <a href="user_setting.php?action=delete&id=<?= $data["id"] ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
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
    let rows = document.querySelectorAll('#userSettingTable tbody tr');

    rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(value) ? '' : 'none';
    });
});
</script>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Setting</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
</head>
<body>