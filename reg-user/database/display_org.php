<?php

include("db_connection.php");

$sql = "SELECT * FROM organizations";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 1) {
    while($row = mysqli_fetch_assoc($result)){
        echo $row["org_id"] . " <br>";
        echo $row["org_description"] . " <br>";
        echo $row["category"] . " <br>";
        echo $row["org_logo"] . " <br>";
        echo $row["org_sites"] . " <br>";
        echo $row["org_contact_email"] . " <br>";
        echo $row["admin_contactnum"] . " <br>";
    }
}else{
    echo "No Organization Found!";
}

$mysqli_close($conn);

?>