<?php
include("config.php");

// here the data base connection file


$dropdown = "select p_name from product";
$result = mysqli_query($conn, $dropdown);

$all_productsName = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<select name="dynamic-dropdown" id="">
   <option value="">select Name </option> 
    <?php
    foreach($all_productsName as $items){
       echo "<option>" . $items['p_name'].  "</option>";
    }
    ?>
</select>


<?php
$ptype="select p_type from product";
$ptype_result= mysqli_query($conn, $ptype);

$allptype= mysqli_fetch_all($ptype_result,MYSQLI_ASSOC);
?>

<select name="dynamic-dropdown" id="">
   <option value="">select type </option> 
    <?php
    foreach($allptype as $type){
       echo "<option>" . $type['p_type'].  "</option>";
    }
    ?>
</select>

