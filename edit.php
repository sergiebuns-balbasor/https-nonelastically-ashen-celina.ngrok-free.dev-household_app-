<?php
include 'db.php';

$id = $_GET['id']; // household ID
// Get household info
$result = $conn->query("SELECT * FROM households WHERE id=$id");
$row = $result->fetch_assoc();

// Get children info
$children_result = $conn->query("SELECT * FROM children WHERE household_id=$id");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Household info
    $father_name = $_POST['father_name'];
    $father_occupation = $_POST['father_occupation'];
    $mother_name = $_POST['mother_name'];
    $mother_occupation = $_POST['mother_occupation'];
    $address = $_POST['address'];
    $members = $_POST['members'];
    $family_income = $_POST['family_income'];
    $house_status = $_POST['house_status'];

    // Update household
    $stmt = $conn->prepare("UPDATE households SET head_name=?, address=?, members=?, father_name=?, father_occupation=?, mother_name=?, mother_occupation=?, family_income=?, house_status=? WHERE id=?");
    $stmt->bind_param("ssissssdsi", $father_name, $father_occupation, $mother_name, $mother_occupation, $address, $members,  $family_income, $house_status, $id);
    $stmt->execute();

    // Delete existing children
    $conn->query("DELETE FROM children WHERE household_id=$id");

    // Insert updated/new children
    $child_names = $_POST['child_name'] ?? [];
    $child_birth_dates = $_POST['child_birth_date'] ?? [];
    $child_sexes = $_POST['child_sex'] ?? [];
    $child_statuses = $_POST['child_civil_status'] ?? [];

    for ($i = 0; $i < count($child_names); $i++) {
        if (!empty($child_names[$i])) {
            $stmt = $conn->prepare("INSERT INTO children (household_id, name, birth_date, sex, civil_status) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issss", $id, $child_names[$i], $child_birth_dates[$i], $child_sexes[$i], $child_statuses[$i]);
            $stmt->execute();
        }
    }

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Household</title>
<style>
body { font-family: Arial, sans-serif; }
input, select { margin-bottom: 8px; }
.child-entry { border: 1px solid #ccc; padding: 8px; margin-bottom: 8px; }
button { padding: 5px 10px; margin-top: 5px; }
</style>
</head>
<body>
<h2>Edit Household</h2>

<form method="POST">
   
 <label>Father's Name:</label><br>
    <input type="text" name="father_name" value="<?php echo htmlspecialchars($row['father_name']); ?>"><br>

    <label>Father's Occupation:</label><br>
    <input type="text" name="father_occupation" value="<?php echo htmlspecialchars($row['father_occupation']); ?>"><br>

    <label>Mother's Name:</label><br>
    <input type="text" name="mother_name" value="<?php echo htmlspecialchars($row['mother_name']); ?>"><br>

    <label>Mother's Occupation:</label><br>
    <input type="text" name="mother_occupation" value="<?php echo htmlspecialchars($row['mother_occupation']); ?>"><br>
   
    <label>Address:</label><br>
    <input type="text" name="address" value="<?php echo htmlspecialchars($row['address']); ?>" required><br>

    <label>No. of Members:</label><br>
    <input type="number" name="members" value="<?php echo htmlspecialchars($row['members']); ?>" required><br>

    <label>Family Income:</label><br>
    <input type="number" step="0.01" name="family_income" value="<?php echo htmlspecialchars($row['family_income']); ?>"><br>

    <label>House Status:</label><br>
    <input type="radio" name="house_status" value="Owned" <?php if($row['house_status']=='Owned') echo 'checked'; ?>> Owned
    <input type="radio" name="house_status" value="Rented" <?php if($row['house_status']=='Rented') echo 'checked'; ?>> Rented
    <input type="radio" name="house_status" value="Other" <?php if($row['house_status']=='Other') echo 'checked'; ?>> Other<br><br>

    <h3>Children</h3>
    <div id="children-container">
        <?php
        while ($child = $children_result->fetch_assoc()) {
            echo '<div class="child-entry">';
            echo '<label>Name:</label><input type="text" name="child_name[]" value="'.htmlspecialchars($child['name']).'">';
            echo '<label>Birth Date:</label><input type="date" name="child_birth_date[]" value="'.htmlspecialchars($child['birth_date']).'">';
            echo '<label>Sex:</label>
                <select name="child_sex[]">
                    <option value="Male" '.($child['sex']=='Male'?'selected':'').'>Male</option>
                    <option value="Female" '.($child['sex']=='Female'?'selected':'').'>Female</option>
                </select>';
            echo '<label>Civil Status:</label>
                <select name="child_civil_status[]">
                    <option value="Single" '.($child['civil_status']=='Single'?'selected':'').'>Single</option>
                    <option value="Married" '.($child['civil_status']=='Married'?'selected':'').'>Married</option>
                    <option value="Other" '.($child['civil_status']=='Other'?'selected':'').'>Other</option>
                </select>';
            echo '</div>';
        }
        ?>
    </div>

    <button type="button" onclick="addChild()">➕ Add Child</button><br><br>

    <input type="submit" value="Update">
</form>

<script>
function addChild() {
    const container = document.getElementById('children-container');
    const div = document.createElement('div');
    div.classList.add('child-entry');
    div.innerHTML = `
        <label>Name:</label><input type="text" name="child_name[]">
        <label>Birth Date:</label><input type="date" name="child_birth_date[]">
        <label>Sex:</label>
        <select name="child_sex[]">
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>
        <label>Civil Status:</label>
        <select name="child_civil_status[]">
            <option value="Single">Single</option>
            <option value="Married">Married</option>
            <option value="Other">Other</option>
        </select>
    `;
    container.appendChild(div);
}
</script>
</body>
</html>
