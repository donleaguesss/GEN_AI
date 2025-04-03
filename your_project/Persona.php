<?php 
include_once('db.php');

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";

// Function to sanitize input
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}
?>

<div class="container" style="max-width: 800px; margin: auto; background: white; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
<a href="home.php" class="btn btn-secondary">Return to Home</a>
    <h1 class="h3 mb-4 text-gray-800">Persona</h1>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="Persona.php?action=add&id=0" class="btn btn-primary">Insert</a>
        <input type="text" id="search" class="form-control w-25" placeholder="Search..." style="margin-left: auto;">
        <br>
    </div>

    <?php
    // Fetch all Profile IDs for dropdown
    $profileQuery = "SELECT id FROM Profile";
    $profileResult = mysqli_query($conn, $profileQuery);
    
    // Insert operation
    if (isset($_REQUEST['save'])) {
        $profile_id = sanitize_input($_REQUEST['profile_id']);
        $persona_code = sanitize_input($_REQUEST['persona_code']);
        $persona_name = sanitize_input($_REQUEST['persona_name']);
        $persona_role = sanitize_input($_REQUEST['persona_role']);
        $persona_designation = sanitize_input($_REQUEST['persona_designation']);
        $persona_email = sanitize_input($_REQUEST['persona_email']);
        $persona_details = sanitize_input($_REQUEST['persona_details']);

        $query = "INSERT INTO `Persona` (`Profile_id`, `persona_code`, `persona_name`, `persona_role`, `persona_designation`, `persona_email`, `persona_details`) 
                  VALUES ('$profile_id', '$persona_code', '$persona_name', '$persona_role', '$persona_designation', '$persona_email', '$persona_details')";
        
        $message = iud_data($query);
        echo "<script>alert('".($message == 'success' ? 'Data saved successfully' : 'Data not saved')."');</script>";
        echo "<script>window.location='Persona.php'</script>";
    }

    // Update operation
    if (isset($_REQUEST['edit'])) {
        $id = $_REQUEST['id'];
        $profile_id = sanitize_input($_REQUEST['profile_id']);
        $persona_code = sanitize_input($_REQUEST['persona_code']);
        $persona_name = sanitize_input($_REQUEST['persona_name']);
        $persona_role = sanitize_input($_REQUEST['persona_role']);
        $persona_designation = sanitize_input($_REQUEST['persona_designation']);
        $persona_email = sanitize_input($_REQUEST['persona_email']);
        $persona_details = sanitize_input($_REQUEST['persona_details']);

        $query = "UPDATE `Persona` SET `Profile_id`='$profile_id', `persona_code`='$persona_code', `persona_name`='$persona_name', 
                  `persona_role`='$persona_role', `persona_designation`='$persona_designation', `persona_email`='$persona_email', 
                  `persona_details`='$persona_details' WHERE id='$id'";

        $message = iud_data($query);
        echo "<script>alert('".($message == 'success' ? 'Data updated successfully' : 'Failed to update')."');</script>";
        echo "<script>window.location='Persona.php'</script>";
    }

    // Delete operation
    if ($action == 'delete') {
        $id = $_REQUEST['id'];
        $query = "DELETE FROM `Persona` WHERE id='$id'";
        
        $message = iud_data($query);
        echo "<script>alert('".($message == 'success' ? 'Data deleted successfully' : 'Failed to delete')."');</script>";
        echo "<script>window.location='Persona.php'</script>";
    }
    ?>

    <?php if ($action == 'edit' || $action == 'add') { 
        $id = $_REQUEST['id'];
        $query = "SELECT * FROM Persona WHERE id='$id'";
        $result = mysqli_query($conn, $query);
        $data = mysqli_fetch_assoc($result);
    ?>
        <form method="post">
            <table class="table table-bordered">
                <tr>
                    <td align="right"><b>Profile ID</b></td>
                    <td>
                        <select name="profile_id" class="form-control" required>
                            <option value="">Select Profile ID</option>
                            <?php 
                            $profileResult = mysqli_query($conn, "SELECT id FROM Profile");
                            while ($profileRow = mysqli_fetch_assoc($profileResult)) { ?>
                                <option value="<?= $profileRow['id']; ?>" <?= ($action == 'edit' && $data['Profile_id'] == $profileRow['id']) ? 'selected' : ''; ?>>
                                    <?= $profileRow['id']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align="right"><b>Persona Code</b></td>
                    <td><input type="text" name="persona_code" class="form-control" value="<?= $action == 'edit' ? $data['persona_code'] : '' ?>" required></td>
                </tr>
                <tr>
                    <td align="right"><b>Name</b></td>
                    <td><input type="text" name="persona_name" class="form-control" value="<?= $action == 'edit' ? $data['persona_name'] : '' ?>" required></td>
                </tr>
                <tr>
                    <td align="right"><b>Role</b></td>
                    <td><input type="text" name="persona_role" class="form-control" value="<?= $action == 'edit' ? $data['persona_role'] : '' ?>" required></td>
                </tr>
                <tr>
                    <td align="right"><b>Designation</b></td>
                    <td><input type="text" name="persona_designation" class="form-control" value="<?= $action == 'edit' ? $data['persona_designation'] : '' ?>" required></td>
                </tr>
                <tr>
                    <td align="right"><b>Email</b></td>
                    <td><input type="email" name="persona_email" class="form-control" value="<?= $action == 'edit' ? $data['persona_email'] : '' ?>" required></td>
                </tr>
                <tr>
                    <td align="right"><b>Details</b></td>
                    <td><textarea name="persona_details" class="form-control"><?= $action == 'edit' ? $data['persona_details'] : '' ?></textarea></td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <button type="submit" class="btn btn-success" name="<?= $action == 'edit' ? 'edit' : 'save' ?>">Save</button>
                    </td>
                </tr>
            </table>
        </form>
    <?php } else { ?>
        <table id="personaTable" class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Profile ID</th>
            <th>Persona Code</th>
            <th>Name</th>
            <th>Role</th>
            <th>Designation</th>
            <th>Email</th>
            <th>Details</th>
            <th>Actions</th> <!-- New Column for Edit/Delete -->
        </tr>
    </thead>
    <tbody id="personaBody">
    <?php
    $query = "SELECT * FROM Persona ORDER BY id DESC";
    $result = mysqli_query($conn, $query);
    while ($data = mysqli_fetch_assoc($result)) {
    ?>
        <tr>
            <td><?= $data["id"] ?></td>
            <td><?= $data["Profile_id"] ?></td>
            <td><?= $data["persona_code"] ?></td>
            <td><?= $data["persona_name"] ?></td>
            <td><?= $data["persona_role"] ?></td>
            <td><?= $data["persona_designation"] ?></td>
            <td><?= $data["persona_email"] ?></td>
            <td><?= $data["persona_details"] ?></td>
            <td>
                <a href="Persona.php?action=edit&id=<?= $data['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="Persona.php?action=delete&id=<?= $data['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>

    <?php } ?>
</div>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Persona</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
</head>
<body>


<script>
document.addEventListener("DOMContentLoaded", function() {
    document.getElementById('search').addEventListener('keyup', function() {
        let value = this.value.toLowerCase().trim();
        let rows = document.querySelectorAll('#personaTable tbody tr');

        rows.forEach(row => {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(value) ? '' : 'none';
        });
    });
});
</script>
