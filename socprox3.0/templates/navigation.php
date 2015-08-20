<div id='cssmenu'>
<ul>
   <li class='active has-sub'><a href='index.php'><span>Tables</span></a>
    <ul>
		<?php 
	  		    $sql='Show tables';
	      		foreach($pdo->query($sql) as $row) {
					echo("<li rel='$row[0]'><a href='#'><span>$row[0]</span></a> </li>");
				}
		?>
     </ul>    
   </li>
   <li rel='overview'><a href='#'><span>Overview</span></a></li>
   <li rel='users'><a href='#'><span>Users</span></a></li>
   <li rel='gameschallenge'><a href='#'><span>Games & Challeges</span></a></li>
   <li rel='simulation'><a href='#'><span>Simulation</span></a></li>
   <li rel='stats'><a href='#'><span>Stats</span></a></li>
 </ul>
</div>