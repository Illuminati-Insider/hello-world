<?php
case "list":
		$subjID=$data['subjectID'];
		$query = "-- Получение списка альбомов для предмета
						SELECT * FROM `albums` 
					WHERE `id` IN (SELECT `Albums_ID` FROM `SubjectAlbums` WHERE `Subjects_ID` LIKE $subjID);";
		if ($result = $mysql->query($query)) {	
			$outputsub = array();
			while ($row = $result->fetch_row())  {
				$outputsub[] = array(
				'albumID' => $row[0], 
				'name' => $row[1]
				);						
			};		
					
		} 	
		else {
				throw403();
		} 
		
		$query = "-- SELECT * FROM `albums` 
					WHERE `id` IN (SELECT `albums_id` FROM `subjectAlbums` WHERE `subjects_id` = $subjectID) 
					OR `id` IN (SELECT `albums_id` FROM `albumClass`
					WHERE `classes_id` 
					IN (SELECT `classes`.`id` FROM `classes` 
					INNER JOIN `classRules` ON `classes`.`rules_id` = `classRules`.`id` 
					WHERE `classRules`.`subjects_id` = $subjectID));";
		if ($result = $mysql->query($query)) {	
			$outputlesson = array();
			while ($row = $result->fetch_row())  {
				$outputlesson[] = array(
				'albumID' => $row[0], 
				'name' => $row[1]
				);						
			};		
					
		} 	
		else {
				throw403();
		} 
	
		$albID = $data['albumID'];
		
		/*$query = "-- Получение списка всех документов альбома
						SELECT `Uploads_ID` FROM `AlbumFiles` WHERE `Albums_ID` LIKE $albID;";
		if ($result = $mysql->query($query)) {	
			$output = array();
			while ($row = $result->fetch_row())  {
				$output[] = array(
				'uplID' => $row[0], 
				);
			};					
		} 	
		else {
				throw403();
		}*/
		
		$documents = array();
		$images = array();
		$archives = array();
		for ($i = 0; $i < count($output); $i = $i + 1) {
		//$query = "-- 
		//				SELECT `FileType`,`FileSize`,`FileName`,`FileExtension` FROM `Uploads` WHERE `ID` LIKE $output[0]['uplID'];";
		$query = "SELECT `FileType`,`FileSize`,`FileName`,`FileExtension` FROM `uploads` WHERE `id` IN (SELECT `uploads_id` FROM `albumFiles` WHERE `albums_id` = $albID);"
						
		if ($result1 = $mysql->query($query)) {	
			$result = array();
			while ($row = $result1->fetch_row())  {
				$result[] = array(
				'FileType' => $row[0], 
				'FileSize' => $row[1], 
				'FileName' => $row[2], 
				'FileExtension' => $row[3]
				);
			};
			switch ($result){
				case ($result[i]['FileType']):	
					$archives[] = array(
					'uplID' => $output[i]['uplID'],
					'FileSize' => $result[i]['FileType'],
					'FileName' =>$result[i]['FileName'],
					'FileExtension' => $result[i]['FileExtension']
					)
				break;
				case "images":
					$images[] = array(
					'uplID' => $output[i]['uplID'],
					'FileSize' => $result[i]['FileType'],
					'FileName' =>$result[i]['FileName'],
					'FileExtension' => $result[i]['FileExtension']
					);
				break;
				case "documents":
					$documents[] = array(
					'uplID' => $output[i]['uplID'],
					'FileSize' => $result[i]['FileType'],
					'FileName' =>$result[i]['FileName'],
					'FileExtension' => $result[i]['FileExtension']
					);
				break;
			}
		}
		else {
				throw403();
		} 
		$output_t0tal = array ('archives' => $archives, 'documents' => $documents, 'images' => $images) 
		/* $output_t0tal - сводный массив со всеми типами
		$images - массив только с картинками, получает айди "загрузки", размер, имя, расширение файла
		$documents - аналогично
		$archives - аналогично
		$result - промежуточный массив
		$outputsub - Получение списка альбомов для предмета без пар,получает $name + $ID
		$outputlesson - Массив предметов + пары, получает $name + $ID
	sad		*/
   } 
?>
