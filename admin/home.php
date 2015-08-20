<div id="contentWrapper">
<div id="content">
<?php
if(isset($_SESSION["messages"])){
	echo "<div class='message'>".FlashMessage::render()."</div>";
}
$query = "SELECT * FROM users";
$result = mysql_query($query);
echo "<table class='gridtable'>";
echo "<thead>Users <span style='margin-left:5px;'><a href='user-form.php'>Add New User</a></span></thead>";
echo "<tbody>";
echo "<tr><th>User Id</th><th>User Name</th><th>Email</th><th>Company</th><th>Country</th><th>User Level</th><th>Acive Status</th><th></th></tr>";
while($row = mysql_fetch_array($result)){
echo "<tr><td>".$row['uid']."</td><td>".$row['uname']."</td><td>".$row['email']."</td><td>".$row['company']."</td><td>".$row['country']."</td><td>".$row['level']."</td><td>".$row['isActive']."</td><td><a href='user-form.php?userId=".$row['uid']."'>Edit</a>&nbsp;";
?>
<a href="user-form-actions.php?action=delete&userId=<?=$row['uid']?>" onclick="return confirm('Are you sure you want delete this user?')" >Delete</a></td></tr>
<?php
}
echo "</tbody>";
echo "</table>";
?>
</div>
</div>