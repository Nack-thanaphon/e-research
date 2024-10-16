<?php
		if(isset($mysqli))
		{
			$mysqli->close();
			unset($mysqli);
		}
?>