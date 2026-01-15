<!DOCTYPE html>
<html>
<head>
    <title>Add Household</title>
</head>
<body>
    <h2>Add Household Details</h2>
    
    <form action="update_household.php" method="POST">
        <label>Father's Name:</label>
        <input type="text" name="father_name"><br>

        <label>Father's Occupation:</label>
        <input type="text" name="father_occupation"><br>

        <label>Mother's Name:</label>
        <input type="text" name="mother_name"><br>

        <label>Mother's Occupation:</label>
        <input type="text" name="mother_occupation"><br>

        <label>Family Income:</label>
        <input type="number" name="family_income" step="0.01"><br>

        <label>House Status:</label>
        <input type="radio" name="house_status" value="Owned"> Owned
        <input type="radio" name="house_status" value="Rented"> Rented
        <input type="radio" name="house_status" value="Other"> Other<br>

        <input type="submit" value="Save">
    </form>
</body>
</html>
