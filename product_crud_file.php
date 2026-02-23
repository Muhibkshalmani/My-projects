<?php
$conn = new mysqli("localhost", "root", "", "productcompany");

// --- 1. THE UPDATE LOGIC ---
if(isset($_POST['update'])){
    $id    = $_POST['p-id']; 
    $name  = $_POST['p-name'];
    $type  = $_POST['p-type'];
    $price = $_POST['p-price'];
    $sql = "UPDATE product SET p_name='$name', p_type='$type', p_price='$price' WHERE id=$id";
    
    if(mysqli_query($conn, $sql)) {
        header("location: product_crud_file.php");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}

// --- 2. THE INSERT LOGIC ---
if(isset($_POST['add'])){
    $product_n = $_POST['p-name'];
    $product_t = $_POST['p-type'];
    $product_p = $_POST['p-price'];

    $sql = "INSERT INTO product (p_name,p_type,p_price) VALUES ('$product_n','$product_t','$product_p')";
    mysqli_query($conn, $sql);
    header("location: product_crud_file.php");
}


// --- THE DELETE LOGIC ---
if(isset($_GET['delete_id'])){
    $id = $_GET['delete_id']; // Catch the ID from the URL
    
    // Create the Delete instruction
    $sql = "DELETE FROM product WHERE id = $id";
    
    // Send it to the database
    mysqli_query($conn, $sql);
    
    // Refresh the page to show the item is gone
    header("location: product_crud_file.php");
    exit();
}

// --- 3. THE EDIT PICKER ---
$e_id = ""; $e_name = ""; $e_type = ""; $e_price = "";
if(isset($_GET['edit_id'])){
    $id = $_GET['edit_id'];
    $res = mysqli_query($conn, "SELECT * FROM product WHERE id=$id");
    if($row = mysqli_fetch_assoc($res)){
        $e_id    = $row['id'];
        $e_name  = $row['p_name'];
        $e_type  = $row['p_type'];
        $e_price = $row['p_price'];
    }
}
?>

<div class="container">
    <form action="product_crud_file.php" method="post">
        <input type="hidden" name="p-id" value="<?php echo $e_id; ?>">

        <input type="text" name="p-name" placeholder="Name" value="<?php echo $e_name; ?>" required>
        <input type="text" name="p-type" placeholder="Type" value="<?php echo $e_type; ?>">
        <input type="number" name="p-price" placeholder="Price" value="<?php echo $e_price; ?>">

        <?php if($e_id != ""): ?>
            <button type="submit" name="update">Update Product</button>
            <a href="product_crud_file.php">Cancel</a>
        <?php else: ?>
            <button type="submit" name="add">Add Product</button>
        <?php endif; ?>
    </form>
</div>






<div class="searbox-main">
    <form action="" method="post">
        <div class="search">
          <label for="search-box"> Search by keyword</label>
          <input type="text" placeholder="Search ..." name="search-box" required>
        </div> 
        <button type="submit" name ="search_btn"> Search </button>
    </form>
<?php
// 1. Start with the "Show All" instruction
$sql = "SELECT * FROM product";

// 2. If the user actually searched for something, change the instruction
if (isset($_POST['search_btn'])) {
    $keyword = $_POST['search-box'];
    $sql = "SELECT * FROM product WHERE p_name LIKE '%$keyword%'";
}

// 3. Run the instruction (Either All or Filtered)
$all = mysqli_query($conn, $sql);
?>

</div>




<div class="p-card-main">
<?php
  // This will now loop through "All" OR "Search Results" automatically!
  while($row = mysqli_fetch_assoc($all)) {
?> 
    <div class="productcard">
        <h4><?php echo $row['p_name']; ?></h4>
        <h4><?php echo $row['p_type']; ?></h4>
        <h4><?php echo $row['p_price']; ?></h4>
        <a href="product_crud_file.php?edit_id=<?php echo $row['id']; ?>"> Edit </a>
        <a href="product_crud_file.php?delete_id=<?php echo $row['id']; ?>" 
           style="color:red;" 
           onclick="return confirm('Are you sure?')"> Delete </a>
    </div> 
<?php 
  } 
?>
</div>














<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family: 'Segoe UI', sans-serif;
}

/* Page background (optional but looks nice) */
body{
   background: linear-gradient(135deg,#4facfe,#00f2fe);
}

/* Container Card */
.container {
    background: #fff;
    width: 380px;
    padding: 35px 30px;
    border-radius: 16px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    animation: fadeIn 0.6s ease;
    margin-bottom: 30px;
    margin: 2rem auto;
}

/* Form Inputs */
.container input{
    width:100%;
    padding:12px 14px;
    margin-bottom:18px;
    border-radius:8px;
    border:1px solid #ddd;
    outline:none;
    font-size:15px;
    transition:0.3s;
}

.container input:focus{
    border-color:#4facfe;
    box-shadow:0 0 0 3px rgba(79,172,254,0.15);
}

/* Submit Button */
.container button{
    width:100%;
    padding:13px;
    border:none;
    border-radius:8px;
    background:linear-gradient(135deg,#4facfe,#00c6ff);
    color:#fff;
    font-size:16px;
    font-weight:600;
    cursor:pointer;
    transition:0.3s;
}

.container button:hover{
    transform:translateY(-2px);
    box-shadow:0 10px 20px rgba(0,0,0,0.2);
}

.container button:active{
    transform:scale(0.98);
}

/* Animation */
@keyframes fadeIn{
    from{opacity:0; transform:translateY(30px);}
    to{opacity:1; transform:translateY(0);}
}

/* Mobile Responsive */
@media(max-width:420px){
    .container{width:90%;}
}

/* Product Card */
.productcard {
    width: 260px;
    background: #fff;
    border-radius: 16px;
    padding: 18px 20px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.12);
    transition: 0.35s;
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.productcard a {
    height: 50px;
    margin: 10px 0px;
    border: 1px solid blue;
    border-radius: 5px;
    color: black;
    justify-content: center;
    display: flex;
    align-items: center;
    text-decoration: none;
}
/* top gradient strip */
.productcard::before{
    content:"";
    position:absolute;
    top:0;
    left:0;
    width:100%;
    height:6px;
    background:linear-gradient(90deg,#4facfe,#00f2fe);
}

/* Hover effect */
.productcard:hover{
    transform:translateY(-8px);
    box-shadow:0 25px 45px rgba(0,0,0,0.18);
}

/* Product Text */
.productcard h4{
    margin:10px 0;
    font-weight:600;
    color:#333;
    font-size:17px;
    border-bottom:1px dashed #eee;
    padding-bottom:8px;
}

/* Last line remove border */
.productcard h4:last-child{
    border:none;
}

/* Label style for dynamic data look */
.productcard h4:nth-child(1)::before{
    content:"📦 Name: ";
    color:#4facfe;
    font-weight:700;
}

.productcard h4:nth-child(2)::before{
    content:"🏷 Type: ";
    color:#00c6ff;
    font-weight:700;
}

.productcard h4:nth-child(3)::before{
    content:"💰 Price: ";
    color:#00b894;
    font-weight:700;
}

/* Container if you show multiple cards */
.cards-wrapper{
    display:flex;
    flex-wrap:wrap;
    gap:20px;
    justify-content:center;
    margin-top:40px;
}
.p-card-main {
    display: flex;
    gap: 30px;
    width: 1200px;
    margin: auto;
    flex-wrap: wrap;
}
</style>

<style>

/* Search Box Wrapper */
.searbox-main{
    display:flex;
    justify-content:center;
    margin-top:40px;
    margin-bottom:40px;
}

/* Form Card */
.searbox-main form{
    display:flex;
    align-items:end;
    gap:12px;
    background:#fff;
    padding:18px 20px;
    border-radius:14px;
    box-shadow:0 15px 35px rgba(0,0,0,0.12);
    transition:0.3s;
}

/* Hover lift */
.searbox-main form:hover{
    transform:translateY(-3px);
    box-shadow:0 20px 45px rgba(0,0,0,0.16);
}

/* Search field container */
.search{
    display:flex;
    flex-direction:column;
}

/* Label */
.search label{
    font-size:13px;
    color:#666;
    margin-bottom:6px;
    font-weight:600;
}

/* Input */
.search input{
    width:260px;
    padding:11px 14px;
    border-radius:8px;
    border:1px solid #ddd;
    outline:none;
    font-size:14px;
    transition:0.3s;
}

.search input:focus{
    border-color:#4facfe;
    box-shadow:0 0 0 3px rgba(79,172,254,0.15);
}

/* Button */
.searbox-main button{
    padding:12px 22px;
    border:none;
    border-radius:8px;
    background:linear-gradient(135deg,#4facfe,#00c6ff);
    color:#fff;
    font-size:15px;
    font-weight:600;
    cursor:pointer;
    transition:0.3s;
}

.searbox-main button:hover{
    transform:translateY(-2px);
    box-shadow:0 10px 20px rgba(0,0,0,0.2);
}

.searbox-main button:active{
    transform:scale(0.96);
}

/* Responsive */
@media(max-width:500px){
    .searbox-main form{
        flex-direction:column;
        align-items:stretch;
    }
    .search input{
        width:100%;
    }
}

</style>
