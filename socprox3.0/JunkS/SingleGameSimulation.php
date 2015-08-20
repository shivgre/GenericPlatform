<?php
include "templates/head.php";

?>
/**
 * Created by PhpStorm.
 * User: afammart
 * Date: 10/27/13
 * Time: 2:55 PM
 */<style>body {	color: black;}</style><body>	<div>
<?php
class SingleChallenge 

{
	public $macAddress1;
	public $macAddress2;
	public function __construct($mac1 = null, $mac2 = null) 

	{
		$macAddressesNeeded = 0;
		
		// echo "<div>Constructor:</div>";
		
		if (isset ( $mac1 )) 

		{
			
			echo "<div>Mac 1: $mac1</div>";
			
			$this->macAddress1 = $mac1;
		} else {
			
			$macAddressesNeeded ++;
		}
		
		if (isset ( $mac2 )) 

		{
			
			// echo "<div>Mac 2: $mac2</div>";
			
			$this->macAddress2 = $mac2;
		} else {
			
			$macAddressesNeeded ++;
		}
		
		if ($macAddressesNeeded) 

		{
			
			// echo "<div>No Mac1, calling GetActiveUsersNotPlaying...</div>";
			
			$this->macAddress1 = $this->GetActiveUserNotPlaying ();
			
			// echo "<div>... returned from GetActiveUsersNotPlaying with value $this->macAddress1</div>";
		}
	}
	private function GetActiveUserNotPlaying() 

	{
		$macArray = array ();
		
		// echo "<div>GetActiveUsersNotPlaying called</div>";
		
		$jsonUsersContent = json_decode ( file_get_contents ( "http://cjcornell.com/bluegame/REST/users" ), true );
		
		// echo "<div>" . "Json Returned : ". "<br/>";
		
		// print_r($jsonUsersContent);
		
		// echo "</div>";
		
		if ($jsonUsersContent ['success'] == 1) 

		{
			
			// echo "<div>Got Successful REST call response</div>";
			
			$usersArray = $jsonUsersContent ['body'];
			
			foreach ( $usersArray as $user ) 

			{
				
				// PrintSection("User");
				
				// print_r($user);
				
				// PrintSection("Mac");
				
				if (! empty ( $user ['m_strMac'] )) 

				{
					
					$macArray [] = $user ['m_strMac'];
				}
			}
			
			PrintSection ( "Mac Array" );
			
			print_r ( $macArray );
		}
		
		// echo "<div>Failed to get REST call response</div>";
	}
	public function ListAvailableGames() 

	{
	}
}

// echo "<div>Creating new SingleChallenge...</div>";

$singleChallenge = new SingleChallenge ();

// echo "<div>...Finished creating class</div>";

$singleChallenge->ListAvailableGames ();

// echo "<div>function returned</div>";
function PrintSection($message = null) 

{
	if (isset ( $message )) 

	{
		
		echo "<br/>" . "<br/>" . "****************************************" . "<br/>";
		
		print_r ( $message );
		
		echo "<br/>" . "****************************************" . "<br/>";
	} else {
		
		echo "<br/>" . "****************************************" . "<br/>";
		
		echo "****************************************" . "<br/>" . "<br/>";
	}
}

?>
</div></body>