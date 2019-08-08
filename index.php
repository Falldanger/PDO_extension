<?php
    define('DB_HOST','localhost');
    define('DB_USER', 'root');
    define('DB_PASSWORD', '');
    define('DB_NAME', 'mysite');

    try{
    $pdo= new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME,DB_USER,DB_PASSWORD,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
	}
	catch(PDOException $e){
		echo "Connection error";
	}

    if(isset($_POST['timez'])){
    	if(is_numeric($_POST['timezone'])&&$_POST['timezone']>=0 && $_POST['timezone']<=23&&$_POST['timezone']!=false){
    		$timezone=$_POST['timezone'];
    		//echo 'Time zone:';print_r($timezone);
    	}
    	else{
    		echo 'Time zone should be from 0 to 23';
    	}
    	if(isset($_POST['offset'])&&(($_POST['offset']%3600)==0)||($_POST['offset']==0)){
    		$offset=$_POST['offset'];
    		//echo '</br>Offset:';print_r($offset);
    	}
    	else{
    		echo '</br>Incorrect value of offset';
    	}
    	if(isset($_POST['country'])&&($_POST['country']!='')){
    		$country=$_POST['country'];
    		//echo '</br>Country:';print_r($country);
    	}
    	else{
    		echo '</br>Incorrect Region';
    	}
    	$query="INSERT INTO `time_zone` (`Country`, `Time zone`, `Offset`) VALUES (:country, :timezone, :offset)";
    	$query=$pdo->prepare($query);
    	
    	$query->execute([':country' => $country,':timezone' => $timezone,':offset' => $offset]
  			);
    }

  //Secord form  

    $query='SELECT * FROM `time_zone`';
	$result=$pdo->query($query);
	
    $table = [];
    while (($row = $result->fetch()) != false) {
        $table[] = $row;
    }

    if (isset($_POST['timezone1'])) {
        $title = $_POST['title']??false;
        $query1 = 'SELECT `Offset` FROM `time_zone` WHERE `Country`=:title';
        $result1=$pdo->prepare($query1);
    	
    	$result1->execute([':title' => $title]);
    	$row1=$result1->fetch();
    	echo 'Selected country: '."<b>$title</b>".';'.' Greenwich offset: ';  print_r($row1[0]); echo ' seconds';
    }

    //$mysqli->close();
?>
<style>
	form.border{
		border:1px solid black;
		width: 400px;
		margin-bottom: 10px;
		padding: 5px;
	}
</style>

<form action="index.php" method="post" name="reg" class="border">
	<p>
		Time zone: <input type="text" name='timezone' value="<?php echo $_POST['timezone']??false; ?>">
	</p>
	<p>
		Offset: <input type="text" name='offset' value="<?php echo $_POST['offset']??false; ?>">
	</p>
	<p>
		Region/Country: <input type="text" name='country' value="<?php echo $_POST['country']??false; ?>">
	</p>
	<p>
		<input type="submit" name="timez" value="Send">
	</p> 
</form>
<form name='reg1' action='index.php' method='post' class="border">
    <select name="title">
        <?php foreach ($table as $key => $value) {
        echo "<option>".$value['Country']."</option><br>"; }
        ?>
    </select>
    <p>
        <input type='submit' name='timezone1' value='Send selection' />
    </p>
</form>