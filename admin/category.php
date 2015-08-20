<?php 
include("includes/header.php");
if((isUserLoggedin())&&(isAdmin()==0)){
	FlashMessage::add('You are not logged in. Please Login.');
	echo '<META http-equiv="refresh" content="0;URL='.BASE_URL.'admin/index.php">';
	exit();
}
?>
<div id="contentWrapper">
<div id="content">
<?php
if(isset($_SESSION["messages"])){
	echo "<div class='message'>".FlashMessage::render()."</div>";
}
$query = "SELECT * FROM project_categories";
$result = mysql_query($query);
echo "<table class='gridtable'>";
echo "<thead>Categories <span style='margin-left:5px;'><a href='category-form.php'>Add New Category</a></span></thead>";
echo "<tbody>";
echo "<tr>
		<th>Category Id</th>
		<th>Category</th>
		<th>Category Description</th>
		<th>Edit/Delete</th>
	</tr>";
while($row = mysql_fetch_array($result)){
echo "<tr>
		<td>".$row['project_category_id']."</td>
		<td>".$row['project_categeory_name']."</td>
		<td>".$row['project_category_descr']."</td>
		<td><a href='category-form.php?catId=".$row['project_category_id']."'>Edit</a>&nbsp;";
?>
<a href="category-form-actions.php?action=delete&catID=<?=$row['project_category_id']?>" onclick="return confirm('Are you sure you want delete this Category?')" >Delete</a></td></tr>
<?php
}
echo "</tbody>";
echo "</table>";
?>

</div>
</div>
<?php include("includes/footer.php"); ?>