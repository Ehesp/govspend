<?php
	
	$data = json_decode(file_get_contents("output.json"), true);
	
	$terms = explode(" ", $_POST['string']);
	
	$output = array();
	
	foreach($data['NOTICES'] as $key => $values)
	{
		$output['META_DATA']["$key"] = 0;
		
		if($key == 'CONTRACT_AWARD')
		{
			foreach($values as $valuekey => $value)
			{
				$title = $value['FD_CONTRACT_AWARD']['OBJECT_CONTRACT_INFORMATION_CONTRACT_AWARD_NOTICE']['DESCRIPTION_AWARD_NOTICE_INFORMATION']['TITLE_CONTRACT'];
				$desc = $value['FD_CONTRACT_AWARD']['OBJECT_CONTRACT_INFORMATION_CONTRACT_AWARD_NOTICE']['DESCRIPTION_AWARD_NOTICE_INFORMATION']['SHORT_CONTRACT_DESCRIPTION'];
				
				$string = $title . " " . $desc;
				
				foreach($terms as $term)
				{
					if(strpos($string, $term) == true) {
						$output['META_DATA']["$key"]++;
						$output['RESULTS']["$key"][] = $value;
					}
				}
				
			}
		}
		if($key == 'CONTRACT')
		{
			foreach($values as $valuekey => $value)
			{
				$title = $value['FD_CONTRACT']['OBJECT_CONTRACT_INFORMATION']['DESCRIPTION_CONTRACT_INFORMATION']['TITLE_CONTRACT'];
				$desc = $value['FD_CONTRACT']['OBJECT_CONTRACT_INFORMATION']['DESCRIPTION_CONTRACT_INFORMATION']['SHORT_CONTRACT_DESCRIPTION'];
				
				$string = $title . " " . $desc;
				
				foreach($terms as $term)
				{
					if(strpos($string, $term) == true) {
						$output['META_DATA']["$key"]++;
						$output['RESULTS']["$key"][] = $value;
					}
				}
				
			}
		}
		if($key == 'PRIOR_INFORMATION')
		{
			foreach($values as $valuekey => $value)
			{
				$title = $value['FD_PRIOR_INFORMATION']['OBJECT_SUPPLIES_SERVICES_PRIOR_INFORMATION']['OBJECT_SUPPLY_SERVICE_PRIOR_INFORMATION']['TITLE_CONTRACT'];
				//$desc = $value['FD_PRIOR_INFORMATION']['OBJECT_SUPPLIES_SERVICES_PRIOR_INFORMATION']['OBJECT_SUPPLY_SERVICE_PRIOR_INFORMATION']['SHORT_CONTRACT_DESCRIPTION'];
				
				$string = $title;
				
				foreach($terms as $term)
				{
					if(strpos($string, $term) == true) {
						$output['META_DATA']["$key"]++;
						$output['RESULTS']["$key"][] = $value;
					}
				}
				
			}
		}
	}
	header('Content-Type: application/json');
	echo json_encode($output);
?>