<?php
include 'db.php';
?>

<?php
include 'db.php';

function calculateAge($birth_date) {
    $birth = new DateTime($birth_date);
    $today = new DateTime();
    $age = $today->diff($birth)->y;
    return $age;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>SERGIE L. BUNANI (Sample System)</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body { font-family: Arial, sans-serif; }
table { border-collapse: collapse; width: 100%; table-layout: fixed; word-wrap: break-word; }
th, td { border: 1px solid #333; padding: 6px; font-size: 14px; vertical-align: top; }
th { background: #f2f2f2; text-align: left; }
a.button {
    padding: 4px 8px;
    background: #007BFF;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    margin-right: 3px;
    font-size: 13px;
}
.group-column { white-space: normal; }
</style>
</head>

<body>

<h2>SERGIE L. BUNANI (Sample System)</h2>

<a class="button" href="add.php">➕ Add Household</a>

<br><br>

<table aria-label="Household Table">
<tr>
    <th>ID</th>
	 <th>Father's Name</th>
    <th>Father's Occupation</th>
    <th>Mother's Name</th>
    <th>Mother's Occupation</th>
    <th>Address</th>
    <th>No. of Members</th>
    <th>Family Income</th>
    <th>House Status</th>
	<th>Children</th>
    <th>Actions</th>
</tr>

<?php
$result = $conn->query("SELECT * FROM households ORDER BY id DESC");

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
	echo "<td>" . htmlspecialchars($row['father_name']) . "</td>";
echo "<td>" . htmlspecialchars($row['father_occupation']) . "</td>";
echo "<td>" . htmlspecialchars($row['mother_name']) . "</td>";
echo "<td>" . htmlspecialchars($row['mother_occupation']) . "</td>";
echo "<td>" . htmlspecialchars($row['address']) . "</td>";
echo "<td>" . htmlspecialchars($row['members']) . "</td>";
echo "<td>" . htmlspecialchars(number_format($row['family_income'],2)) . "</td>";
echo "<td>" . htmlspecialchars($row['house_status']) . "</td>";

// Fetch children for this household
$children_result = $conn->query("SELECT * FROM children WHERE household_id={$row['id']}");
$children_list = [];
while ($child = $children_result->fetch_assoc()) {
    $age = calculateAge($child['birth_date']);
    $children_list[] = htmlspecialchars($child['name']) . " ({$age}/" . htmlspecialchars($child['sex']) . "/" . htmlspecialchars($child['civil_status']) . ")";
}
echo "<td>" . implode(", ", $children_list) . "</td>";


    echo "<td>
            <a class='button' href='edit.php?id={$row['id']}'>✏️ Edit</a>
            <a class='button' href='delete.php?id={$row['id']}' 
               onclick=\"return confirm('Are you sure you want to delete this record?');\">
               🗑 Delete
            </a>
          </td>";
    echo "</tr>";
}
?>

</table>

</body>
</html>
