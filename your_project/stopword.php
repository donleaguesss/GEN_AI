<?php 
include('db.php');

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";

// Function to sanitize input
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

?>

<div class="container" style="max-width: 800px; margin: auto; background: white; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
<a href="home.php" class="btn btn-secondary">Return to Home</a>   
<h1 class="h3 mb-4 text-gray-800">Stop Words</h1>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <a href="Stopword.php?action=add&id=0" class="btn btn-primary">Insert</a>
    </div><br>
    <input type="text" id="search" class="form-control w-25" placeholder="Search..." style="margin-left: auto;">
</div>

    <?php
    // Insert operation
    if (isset($_REQUEST['save'])) {
        $profile_id  = sanitize_input($_REQUEST['profile_id']);
        $stopword_code  = sanitize_input($_REQUEST['stopword_code']);
        $stopword_word  = sanitize_input($_REQUEST['stopword_word']);
        $stopword_status  = sanitize_input($_REQUEST['stopword_status']);

        $query = "INSERT INTO `Stopword` (`Profile_id`, `Stopword_code`, `Stopword_word`, `Stopword_status`) 
                  VALUES ('$profile_id', '$stopword_code', '$stopword_word', '$stopword_status')";
        
        // Store stopword in the corpus table
        $corpus_query = "INSERT INTO Corpus (entity_type, entity_id, data) 
                         VALUES ('stopword', LAST_INSERT_ID(), '$stopword_word')";
        mysqli_query($conn, $corpus_query);
        
        $message = iud_data($query);
        echo "<script>alert('".($message == 'success' ? 'Data saved successfully' : 'Data not saved')."');</script>";
        echo "<script>window.location='Stopword.php'</script>";
    }

    // Update operation
    if (isset($_REQUEST['edit'])) {
        $id = $_REQUEST['id'];
        $profile_id  = sanitize_input($_REQUEST['profile_id']);
        $stopword_code  = sanitize_input($_REQUEST['stopword_code']);
        $stopword_word  = sanitize_input($_REQUEST['stopword_word']);
        $stopword_status  = sanitize_input($_REQUEST['stopword_status']);

        $query = "UPDATE `Stopword` SET `Profile_id`='$profile_id', `Stopword_code`='$stopword_code', `Stopword_word`='$stopword_word', 
                  `Stopword_status`='$stopword_status' WHERE id='$id'";
        
        $message = iud_data($query);
        echo "<script>alert('".($message == 'success' ? 'Data updated successfully' : 'Failed to update')."');</script>";
        echo "<script>window.location='Stopword.php'</script>";
    }

    // Delete operation
    if ($action == 'delete') {
        $id = $_REQUEST['id'];
        $query = "DELETE FROM `Stopword` WHERE id='$id'";

        $message = iud_data($query);
        echo "<script>alert('".($message == 'success' ? 'Data deleted successfully' : 'Failed to delete')."');</script>";
        echo "<script>window.location='Stopword.php'</script>";
    }
    ?>

    <table id="stopwordTable" class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Profile ID</th>
                <th>Stopword Code</th>
                <th>Stopword Word</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $query = "SELECT * FROM Stopword ORDER BY id DESC";
        $result = mysqli_query($conn, $query);
        while ($data = mysqli_fetch_assoc($result)) {
        ?>
            <tr>
                <td><?= $data["id"] ?></td>
                <td><?= $data["Profile_id"] ?></td>
                <td><?= $data["Stopword_code"] ?></td>
                <td><?= $data["Stopword_word"] ?></td>
                <td><?= $data["Stopword_status"] ?></td>
                <td>
                    <a href="Stopword.php?action=edit&id=<?= $data["id"] ?>" class="btn btn-success">Edit</a>
                    <a href="Stopword.php?action=delete&id=<?= $data["id"] ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<script>
document.getElementById('search').addEventListener('keyup', function() {
    let value = this.value.toLowerCase();
    let rows = document.querySelectorAll('#stopwordTable tbody tr');

    rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(value) ? '' : 'none';
    });
});
</script>
