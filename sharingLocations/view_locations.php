<?php
// Read the CSV file and create a table to display the list of locations
$file = fopen("./locations.csv", "r") or die("Unable to open file!");
$locations = array();

while (($data = fgetcsv($file)) !== false) {
    array_push($locations, $data);
}
fclose($file);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Lis kote</title>
    <link rel="stylesheet" type="text/css" href="./view.css">
</head>
<body>
    <h1>Lis denye kote imigrasyon te ye</h1>
    <?php if (empty($locations)) { ?>
        <p>No locations saved yet.</p>
    <?php } else { ?>
        <table>
            <tr>
                <th>Latitid</th>
                <th>Longiti</th>
                <th>ki vil</th>
                <th>ki peyi</th>
                <th>ki riyel ou avni</th>
                <th>ki dat ak lè</th>
                <th>gadel nan kat la</th>
            </tr>
            <?php foreach ($locations as $location) { ?>
                <tr>
                    <td><?php echo $location[0]; ?></td>
                    <td><?php echo $location[1]; ?></td>
                    <td><?php echo $location[2]; ?></td>
                    <td><?php echo $location[3]; ?></td>
                    <td><?php echo $location[4]; ?></td>
                    <td><?php echo $location[5]; ?></td>
                    <td><a href="https://www.google.com/maps/search/?api=1&query=<?php echo $location[0]; ?>,<?php echo $location[1]; ?>" target="_blank">al gade</a></td>
                </tr>
            <?php } ?>
        </table>
    <?php } ?>
    <br>
    <a href="index.html">retounen</a>
</body>
</html>
