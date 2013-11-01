<?php
	
		// URL etc... In this instance for simplicity the XML is in a text file
		$fileContents = file_get_contents("xml-sample.txt");

		$fileContents = str_replace(array("\n", "\r", "\t"), '', $fileContents);

		$fileContents = trim(str_replace('"', "'", $fileContents));

		$simpleXml = new SimpleXMLElement($fileContents);
		
		print json_encode($simpleXml);

?>