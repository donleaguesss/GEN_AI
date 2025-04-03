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

// Fetch persona, annotator, and stopword data for corpus
$persona_query = "SELECT id, corpus_data FROM Persona";
$persona_result = mysqli_query($conn, $persona_query);

$annotator_query = "SELECT id, corpus_data FROM Annotator";
$annotator_result = mysqli_query($conn, $annotator_query);

$stopword_query = "SELECT id, Stopword_word FROM Stopword WHERE is_corpus = TRUE";
$stopword_result = mysqli_query($conn, $stopword_query);

// Function to generate a prompt based on the corpus
function generatePrompt($profile_id) {
    global $conn;

    // Fetch persona details
    $persona_query = "SELECT corpus_data FROM Persona WHERE Profile_id = ?";
    $stmt1 = $conn->prepare($persona_query);
    $stmt1->bind_param("i", $profile_id);
    $stmt1->execute();
    $persona_result = $stmt1->get_result();
    $persona = $persona_result->fetch_assoc();

    // Fetch annotator contributions
    $annotator_query = "SELECT corpus_data FROM Annotator WHERE Profile_id = ?";
    $stmt2 = $conn->prepare($annotator_query);
    $stmt2->bind_param("i", $profile_id);
    $stmt2->execute();
    $annotator_result = $stmt2->get_result();
    $annotator = $annotator_result->fetch_assoc();

    // Fetch a random stopword to exclude
    $stopword_query = "SELECT Stopword_word FROM Stopword WHERE is_corpus = TRUE ORDER BY RAND() LIMIT 1";
    $stopword_result = $conn->query($stopword_query);
    $stopword = $stopword_result->fetch_assoc();

    // Construct the prompt
    $prompt = "Create a " . json_decode($persona['corpus_data'])[0] . 
              " style prompt, avoiding the word '" . $stopword['Stopword_word'] . 
              "', considering annotator expertise in " . 
              json_decode($annotator['corpus_data'])[0] . ".";

    return $prompt;
}

// Insert operation
if (isset($_POST['save'])) {
    $prompt_code = sanitize_input($_POST['prompt_code']);
    $profile_id = sanitize_input($_POST['profile_id']);
    $prompt_string = generatePrompt($profile_id); // Generate prompt dynamically

    $query = "INSERT INTO `Prompt` (`Prompt_code`, `Profile_id`, `Prompt_String`) 
              VALUES ('$prompt_code', '$profile_id', '$prompt_string')";
    
    $message = iud_data($query);
    echo "<script>alert('".($message == 'success' ? 'Data saved successfully' : 'Data not saved')."');</script>";
    echo "<script>window.location='create_prompt.php'</script>";
}

// Update operation
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $prompt_code = sanitize_input($_POST['prompt_code']);
    $profile_id = sanitize_input($_POST['profile_id']);
    $prompt_string = generatePrompt($profile_id); // Regenerate prompt dynamically

    $query = "UPDATE `Prompt` SET `Prompt_code`='$prompt_code', `Profile_id`='$profile_id', `Prompt_String`='$prompt_string' WHERE id='$id'";
    
    $message = iud_data($query);
    echo "<script>alert('".($message == 'success' ? 'Data updated successfully' : 'Failed to update')."');</script>";
    echo "<script>window.location='create_prompt.php'</script>";
}

// Delete operation
if ($action == 'delete') {
    $id = $_REQUEST['id'];
    $query = "DELETE FROM `Prompt` WHERE id='$id'";
    
    $message = iud_data($query);
    echo "<script>alert('".($message == 'success' ? 'Data deleted successfully' : 'Failed to delete')."');</script>";
    echo "<script>window.location='create_prompt.php'</script>";
}
?>

<div class="container">
<a href="home.php" class="btn btn-secondary">Return to Home</a>
<h1 class="h3 mb-4 text-gray-800">Create Prompt</h1>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <a href="create_prompt.php?action=add&id=0" class="btn btn-primary">Insert</a>
    </div><br>
    <input type="text" id="search" class="form-control w-25" placeholder="Search..." style="margin-left: auto;">
</div>

<table id="promptTable" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Prompt Code</th>
            <th>Profile ID</th>
            <th>Prompt String</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $query = "SELECT * FROM Prompt ORDER BY id DESC";
    $result = mysqli_query($conn, $query);
    while ($data = mysqli_fetch_assoc($result)) {
    ?>
        <tr>
            <td><?= $data["id"] ?></td>
            <td><?= $data["Prompt_code"] ?></td>
            <td><?= $data["Profile_id"] ?></td>
            <td><?= $data["Prompt_String"] ?></td>
            <td>
                <a href="create_prompt.php?action=edit&id=<?= $data["id"] ?>" class="btn btn-success">Edit</a>
                <a href="create_prompt.php?action=delete&id=<?= $data["id"] ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>

<?php if ($action == 'edit' || $action == 'add') { 
    $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : null;
    $query = "SELECT * FROM Prompt WHERE id='$id'";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);
?>
    <form method="post">
        <input type="hidden" name="id" value="<?= $action == 'edit' ? $data['id'] : '' ?>">
        <table class="table table-bordered">
            <tr>
                <td align="right"><b>Prompt Code</b></td>
                <td><input type="text" name="prompt_code" class="form-control" value="<?= $action == 'edit' ? $data['Prompt_code'] : '' ?>" required></td>
            </tr>
            <tr>
                <td align="right"><b>Profile ID</b></td>
                <td>
                    <select name="profile_id" class="form-control" required>
                        <option value="">Select Profile</option>
                        <?php while ($profile = mysqli_fetch_assoc($profile_result)) { ?>
                            <option value="<?= $profile['id'] ?>" <?= ($action == 'edit' && $data['Profile_id'] == $profile['id']) ? 'selected' : '' ?>>
                                <?= $profile['id'] ?>
                            </option>
                        <?php } ?>
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
<?php } ?>
</div>
