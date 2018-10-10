 <?php
session_start();
//if (isset($_GET['getCategories']) && function_exists($_GET['getCategories'])){

// Create connection
$conn = mysqli_connect("localhost", "root", "root","library");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$purpose = $_POST["purpose"];

if($purpose == 'getcategories') {
	$query = "SELECT * from `category` where NULLIF(`category`.`deletedyn`,'') IS NULL";
	$result = mysqli_query($conn,$query);

	if ($result->num_rows > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			$names[] = array(			
				'id' => $row['id'],
				'name' => $row['name'],
				'image' => $row['image']
			);
		}
		echo json_encode($names);
	}
	else{
		echo $purpose;
	}
}
else if ($purpose == 'getsubcategories') {
	$category = $_POST["category"];
	$query = "SELECT * from `subcategory` where `categoryid` = $category and NULLIF(`subcategory`.`deletedyn`,'') IS NULL";
	$result = mysqli_query($conn,$query);
	
	if ($result->num_rows > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			$names[] = array(			
				'id' => $row['id'],
				'category_id' => $category,
				'name' => $row['name'],
				'image' => $row['image']
			);
		}
		echo json_encode($names);
	}
	else{
		echo "<b>Sub Category not found</b>";
	}
}
else if ($purpose == 'getbooks') {
	$category = $_POST["category"];	
	$subcategory = $_POST["subcategory"];
	$count = $_POST["count"];
	$count = $count * 8;	
	$query = "SELECT `books`.`pid` as 'bookid',`book_name`,`books`.`publisher`,`books`.`features`,`books`.`images`,`publisherid`,`featureid`,`books`.`content`,`books`.`no_of_copies` from (select `book`.`content`,`book`.`no_of_copies`,`publisher`.`id` as 'publisherid',`features`.`id` as 'featureid',`book`.`id` as 'pid',`book`.`name` as 'book_name',`publisher`.`name` as 'publisher',GROUP_CONCAT(`features`.`name`) as 'features',`book`.`images` as 'images' from `book`,`category`,`subcategory`,`publisher`,`features`, `book_features`where `book`.`id` = `book_features`.`bookid` and `book`.`categoryid` = `category`.`id` and `book`.`subcategoryid` = `subcategory`.`id` and `book`.`publisherid` = `publisher`.`id` and `book_features`.`featureid` = `features`.`id` and `book`.`categoryid` = $category and `book`.`subcategoryid` = $subcategory and NULLIF(`book`.`deletedyn`,'') IS NULL group by `book`.`name`,`category`.`name`,`subcategory`.`name`,`publisher`.`name`) books order by `books`.`pid` LIMIT $count, 8";

	$result = mysqli_query($conn,$query);
	
	if ($result->num_rows > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			$names[] = array(
				'id' => $row['bookid'],
				'name' => $row['book_name'],
				'images' => $row['images'],		
				'publisher' => $row['publisher'],
				'features' => $row['features'],
				'publisherid' => $row['publisherid'],
				'desc' => $row['content'],
				'no_of_copies' => $row['no_of_copies'],
				'featureid' => $row['featureid']
			);
		}
		echo json_encode($names);
	}
	else{
		echo "<b>book not found</b>";
	}

}
else if ($purpose == 'searchbooks') {
	$search = $_POST["query"];
	$count = $_POST["count"];
	$count = $count * 8;
	$query = "SELECT `books`.`pid` as 'bookid',`book_name`,`books`.`publisher`,`books`.`features`,`books`.`images`,`publisherid`,`featureid` ,`books`.`content`,`books`.`no_of_copies` from (select `book`.`content`,`book`.`no_of_copies`,`publisher`.`id` as 'publisherid',`features`.`id` as 'featureid',`book`.`id` as 'pid',`book`.`name` as 'book_name',`publisher`.`name` as 'publisher',GROUP_CONCAT(`features`.`name`) as 'features',`book`.`images` as 'images' from `book`,`category`,`subcategory`,`publisher`,`features`, `book_features`where `book`.`id` = `book_features`.`bookid` and `book`.`categoryid` = `category`.`id` and `book`.`subcategoryid` = `subcategory`.`id` and `book`.`publisherid` = `publisher`.`id` and `book_features`.`featureid` = `features`.`id` and lower(`book`.`name`) LIKE '%$search%' and NULLIF(`book`.`deletedyn`,'') IS NULL group by `book`.`name`,`category`.`name`,`subcategory`.`name`,`publisher`.`name`) books order by `books`.`pid` LIMIT 0, 8";

	$result = mysqli_query($conn,$query);
	
	if ($result->num_rows > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			$names[] = array(
				'id' => $row['bookid'],
				'name' => $row['book_name'],
				'images' => $row['images'],		
				'publisher' => $row['publisher'],
				'features' => $row['features'],
				'publisherid' => $row['publisherid'],
					'desc' => $row['content'],
					'no_of_copies' => $row['no_of_copies'],
				'featureid' => $row['featureid']
			);
		}
		echo json_encode($names);
	}
	else{
		echo "<b>book not found</b>";
	}

}
else if ($purpose == 'getpublishers') {
	$category = $_POST["category"];	
	$subcategory = $_POST["subcategory"];
	$publisher = $_POST["values"];
	$count = $_POST["count"];
	$features = $_POST["featurevalues"];
	$count = $count * 8;
	if($features != '' && $publisher != ''){
		$query = "SELECT `books`.`pid` as 'bookid',`book_name`,`books`.`publisher`,`books`.`features`,`books`.`images`,`publisherid`,`featureid` ,`books`.`content`,`books`.`no_of_copies` from (select `book`.`content`,`book`.`no_of_copies`, `publisher`.`id` as 'publisherid',`features`.`id` as 'featureid',`book`.`id` as 'pid',`book`.`name` as 'book_name',`publisher`.`name` as 'publisher',GROUP_CONCAT(`features`.`name`) as 'features',`book`.`images` as 'images' from `book`,`category`,`subcategory`,`publisher`,`features`, `book_features`where `book`.`id` = `book_features`.`bookid` and `book`.`categoryid` = `category`.`id` and `book`.`subcategoryid` = `subcategory`.`id` and `book`.`publisherid` = `publisher`.`id` and `book_features`.`featureid` = `features`.`id` and `book`.`categoryid` = $category and `book`.`subcategoryid` = $subcategory and `publisher`.`name` in ($publisher) and `features`.`name` in ($features) and NULLIF(`book`.`deletedyn`,'') IS NULL group by `book`.`name`,`category`.`name`,`subcategory`.`name`,`publisher`.`name`) books order by `books`.`pid` LIMIT $count, 8";

		$result = mysqli_query($conn,$query);
		
		if ($result->num_rows > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				$names[] = array(
					'id' => $row['bookid'],
					'name' => $row['book_name'],
					'images' => $row['images'],	
					'publisher' => $row['publisher'],
					'desc' => $row['content'],
					'no_of_copies' => $row['no_of_copies'],
					'features' => $row['features']
				);
			}
			echo json_encode($names);
		}
		else{
			echo "<b>publisher not found</b>";
		}
	}
	else if($features != '' && $publisher == ''){
		$query = "SELECT `books`.`pid` as 'bookid',`book_name`,`books`.`publisher`,`books`.`features`,`books`.`images`,`publisherid`,`featureid` ,`books`.`content`,`books`.`no_of_copies` from (select `book`.`content`,`book`.`no_of_copies`,`publisher`.`id` as 'publisherid',`features`.`id` as 'featureid',`book`.`id` as 'pid',`book`.`name` as 'book_name',`publisher`.`name` as 'publisher',GROUP_CONCAT(`features`.`name`) as 'features',`book`.`images` as 'images' from `book`,`category`,`subcategory`,`publisher`,`features`, `book_features`where `book`.`id` = `book_features`.`bookid` and `book`.`categoryid` = `category`.`id` and `book`.`subcategoryid` = `subcategory`.`id` and `book`.`publisherid` = `publisher`.`id` and `book_features`.`featureid` = `features`.`id` and `book`.`categoryid` = $category and `book`.`subcategoryid` = $subcategory and `features`.`name` in ($features) and NULLIF(`book`.`deletedyn`,'') IS NULL group by `book`.`name`,`category`.`name`,`subcategory`.`name`,`publisher`.`name`) books order by `books`.`pid` LIMIT $count, 8";

		$result = mysqli_query($conn,$query);
		
		if ($result->num_rows > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				$names[] = array(
					'id' => $row['bookid'],
					'name' => $row['book_name'],
					'images' => $row['images'],		
					'publisher' => $row['publisher'],
					'desc' => $row['content'],
					'no_of_copies' => $row['no_of_copies'],
					'features' => $row['features']
				);
			}
			echo json_encode($names);
		}
		else{
			echo "<b>publisher not found</b>";
		}
	}
	else {
		$query = "SELECT `books`.`pid` as 'bookid',`book_name` as 'recentreview' ,`books`.`publisher`,`books`.`features`,`books`.`images`,`publisherid`,`featureid` ,`books`.`content`,`books`.`no_of_copies` from (select `book`.`content`,`book`.`no_of_copies`, `publisher`.`id` as 'publisherid',`features`.`id` as 'featureid',`book`.`id` as 'pid',`book`.`name` as 'book_name',`publisher`.`name` as 'publisher',GROUP_CONCAT(`features`.`name`) as 'features',`book`.`images` as 'images' from `book`,`category`,`subcategory`,`publisher`,`features`, `book_features`where `book`.`id` = `book_features`.`bookid` and `book`.`categoryid` = `category`.`id` and `book`.`subcategoryid` = `subcategory`.`id` and `book`.`publisherid` = `publisher`.`id` and `book_features`.`featureid` = `features`.`id` and `book`.`categoryid` = $category and `book`.`subcategoryid` = $subcategory and `publisher`.`name` in ($publisher) and NULLIF(`book`.`deletedyn`,'') IS NULL group by `book`.`name`,`category`.`name`,`subcategory`.`name`,`publisher`.`name`) books order by `books`.`pid` LIMIT $count, 8";

		$result = mysqli_query($conn,$query);
		
		if ($result->num_rows > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				$names[] = array(
					'id' => $row['bookid'],
					'name' => $row['book_name'],
					'images' => $row['images'],		
					'publisher' => $row['publisher'],
					'desc' => $row['content'],
					'no_of_copies' => $row['no_of_copies'],
					'features' => $row['features']
				);
			}
			echo json_encode($names);
		}
		else{
			echo "<b>publisher not found</b>";
		}
	}
}
else if ($purpose == 'searchpublishers') {
	$search = $_POST["query"];	
	$publisher = $_POST["values"];
	$count = $_POST["count"];
	$features = $_POST["featurevalues"];
	$count = $count * 8;
	if($features != '' && $publisher != ''){
		$query = "SELECT `books`.`pid` as 'bookid',`book_name`,`books`.`publisher`,`books`.`features`,`books`.`images`,`publisherid`,`featureid` ,`books`.`content`,`books`.`no_of_copies` from (select `book`.`content`,`book`.`no_of_copies`, `publisher`.`id` as 'publisherid',`features`.`id` as 'featureid',`book`.`id` as 'pid',`book`.`name` as 'book_name',`publisher`.`name` as 'publisher',GROUP_CONCAT(`features`.`name`) as 'features',`book`.`images` as 'images' from `book`,`category`,`subcategory`,`publisher`,`features`, `book_features`where `book`.`id` = `book_features`.`bookid` and `book`.`categoryid` = `category`.`id` and `book`.`subcategoryid` = `subcategory`.`id` and `book`.`publisherid` = `publisher`.`id` and `book_features`.`featureid` = `features`.`id` and lower(`book`.`name`) LIKE '%$search%' and `publisher`.`name` in ($publisher) and `features`.`name` in ($features) and NULLIF(`book`.`deletedyn`,'') IS NULL group by `book`.`name`,`category`.`name`,`subcategory`.`name`,`publisher`.`name`) books order by `books`.`pid` LIMIT $count, 8";

		$result = mysqli_query($conn,$query);
	
		if ($result->num_rows > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				$names[] = array(
					'id' => $row['bookid'],
					'name' => $row['book_name'],
					'images' => $row['images'],		
					'publisher' => $row['publisher'],
					'desc' => $row['content'],
					'no_of_copies' => $row['no_of_copies'],
					'features' => $row['features']
				);
			}
			echo json_encode($names);
		}
		else{
			echo "<b>publisher not found</b>";
		}
	}
	else if($features != '' && $publisher == ''){
		$query = "SELECT `books`.`pid` as 'bookid',`book_name`,`books`.`publisher`,`books`.`features`,`books`.`images`,`publisherid`,`featureid` ,`books`.`content`,`books`.`no_of_copies` from (select `book`.`content`,`book`.`no_of_copies`,`publisher`.`id` as 'publisherid',`features`.`id` as 'featureid',`book`.`id` as 'pid',`book`.`name` as 'book_name',`publisher`.`name` as 'publisher',GROUP_CONCAT(`features`.`name`) as 'features',`book`.`images` as 'images' from `book`,`category`,`subcategory`,`publisher`,`features`, `book_features`where `book`.`id` = `book_features`.`bookid` and `book`.`categoryid` = `category`.`id` and `book`.`subcategoryid` = `subcategory`.`id` and `book`.`publisherid` = `publisher`.`id` and `book_features`.`featureid` = `features`.`id` and lower(`book`.`name`) LIKE '%$search%' and `features`.`name` in ($features) and NULLIF(`book`.`deletedyn`,'') IS NULL group by `book`.`name`,`category`.`name`,`subcategory`.`name`,`publisher`.`name`) books order by `books`.`pid` LIMIT $count, 8";

		$result = mysqli_query($conn,$query);
	
		if ($result->num_rows > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				$names[] = array(
					'id' => $row['bookid'],
					'name' => $row['book_name'],
					'images' => $row['images'],		
					'publisher' => $row['publisher'],
					'desc' => $row['content'],
					'no_of_copies' => $row['no_of_copies'],
					'features' => $row['features']
				);
			}
			echo json_encode($names);
		}
		else{
			echo "<b>publisher not found</b>";
		}
	}
	else {
		$query = "SELECT `books`.`pid` as 'bookid',`book_name`,`books`.`publisher`,`books`.`features`,`books`.`images`,`publisherid`,`featureid` ,`books`.`content`,`books`.`no_of_copies` from (select `book`.`content`,`book`.`no_of_copies`, `publisher`.`id` as 'publisherid',`features`.`id` as 'featureid',`book`.`id` as 'pid',`book`.`name` as 'book_name',`publisher`.`name` as 'publisher',GROUP_CONCAT(`features`.`name`) as 'features',`book`.`images` as 'images' from `book`,`category`,`subcategory`,`publisher`,`features`, `book_features`where `book`.`id` = `book_features`.`bookid` and `book`.`categoryid` = `category`.`id` and `book`.`subcategoryid` = `subcategory`.`id` and `book`.`publisherid` = `publisher`.`id` and `book_features`.`featureid` = `features`.`id` and lower(`book`.`name`) LIKE '%$search%' and `publisher`.`name` in ($publisher) and NULLIF(`book`.`deletedyn`,'') IS NULL group by `book`.`name`,`category`.`name`,`subcategory`.`name`,`publisher`.`name`) books order by `books`.`pid` LIMIT $count, 8";

		$result = mysqli_query($conn,$query);
	
		if ($result->num_rows > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				$names[] = array(
					'id' => $row['bookid'],
					'name' => $row['book_name'],
					'images' => $row['images'],		
					'publisher' => $row['publisher'],
					'desc' => $row['content'],
					'no_of_copies' => $row['no_of_copies'],
					'features' => $row['features']
				);
			}
			echo json_encode($names);
		}
		else{
			echo "<b>publisher not found</b>";
		}
	}

}
else if ($purpose == 'getfeatures') {
	$category = $_POST["category"];	
	$subcategory = $_POST["subcategory"];
	$features = $_POST["values"];
	$publishers = $_POST["publishervalues"];
	$count = $_POST["count"];
	$count = $count * 8;
	if($publishers != '' && $features != ''){
		$query = "SELECT `books`.`pid` as 'bookid',`book_name`,`books`.`publisher`,`books`.`features`,`books`.`images`,`publisherid`,`featureid` ,`books`.`content`,`books`.`no_of_copies` from (select `book`.`content`,`book`.`no_of_copies`,`publisher`.`id` as 'publisherid',`features`.`id` as 'featureid',`book`.`id` as 'pid',`book`.`name` as 'book_name',`publisher`.`name` as 'publisher',GROUP_CONCAT(`features`.`name`) as 'features',`book`.`images` as 'images' from `book`,`category`,`subcategory`,`publisher`,`features`, `book_features`where `book`.`id` = `book_features`.`bookid` and `book`.`categoryid` = `category`.`id` and `book`.`subcategoryid` = `subcategory`.`id` and `book`.`publisherid` = `publisher`.`id` and `book_features`.`featureid` = `features`.`id` and `book`.`categoryid` = $category and `book`.`subcategoryid` = $subcategory and `features`.`name` in ($features) and `publisher`.`name` in ($publishers) and NULLIF(`book`.`deletedyn`,'') IS NULL group by `book`.`name`,`category`.`name`,`subcategory`.`name`,`publisher`.`name`) books order by `books`.`pid` LIMIT $count, 8";

		$result = mysqli_query($conn,$query);
	
		if ($result->num_rows > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				$names[] = array(
					'id' => $row['bookid'],
					'name' => $row['book_name'],
					'images' => $row['images'],		
					'publisher' => $row['publisher'],
					'desc' => $row['content'],
					'no_of_copies' => $row['no_of_copies'],
					'features' => $row['features']
				);
			}
			echo json_encode($names);
		}
		else{
			echo "<b>Feature not found</b>";
		}
	}
	else if($publishers != '' && $features == ''){
		$query = "SELECT `books`.`pid` as 'bookid',`book_name`,`books`.`publisher`,`books`.`features`,`books`.`images`,`publisherid`,`featureid` ,`books`.`content`,`books`.`no_of_copies` from (select `book`.`content`,`book`.`no_of_copies`,`publisher`.`id` as 'publisherid',`features`.`id` as 'featureid',`book`.`id` as 'pid',`book`.`name` as 'book_name',`publisher`.`name` as 'publisher',GROUP_CONCAT(`features`.`name`) as 'features',`book`.`images` as 'images' from `book`,`category`,`subcategory`,`publisher`,`features`, `book_features`where `book`.`id` = `book_features`.`bookid` and `book`.`categoryid` = `category`.`id` and `book`.`subcategoryid` = `subcategory`.`id` and `book`.`publisherid` = `publisher`.`id` and `book_features`.`featureid` = `features`.`id` and `book`.`categoryid` = $category and `book`.`subcategoryid` = $subcategory and `publisher`.`name` in ($publishers) and NULLIF(`book`.`deletedyn`,'') IS NULL group by `book`.`name`,`category`.`name`,`subcategory`.`name`,`publisher`.`name`) books order by `books`.`pid` LIMIT $count, 8";

		$result = mysqli_query($conn,$query);
	
		if ($result->num_rows > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				$names[] = array(
					'id' => $row['bookid'],
					'name' => $row['book_name'],
					'images' => $row['images'],		
					'publisher' => $row['publisher'],
					'desc' => $row['content'],
					'no_of_copies' => $row['no_of_copies'],
					'features' => $row['features']
				);
			}
			echo json_encode($names);
		}
		else{
			echo "<b>Feature not found</b>";
		}
	}
	else {
		$query = "SELECT `books`.`pid` as 'bookid',`book_name`,`books`.`publisher`,`books`.`features`,`books`.`images`,`publisherid`,`featureid` ,`books`.`content`,`books`.`no_of_copies` from (select `book`.`content`,`book`.`no_of_copies`, `publisher`.`id` as 'publisherid',`features`.`id` as 'featureid',`book`.`id` as 'pid',`book`.`name` as 'book_name',`publisher`.`name` as 'publisher',GROUP_CONCAT(`features`.`name`) as 'features',`book`.`images` as 'images' from `book`,`category`,`subcategory`,`publisher`,`features`, `book_features`where `book`.`id` = `book_features`.`bookid` and `book`.`categoryid` = `category`.`id` and `book`.`subcategoryid` = `subcategory`.`id` and `book`.`publisherid` = `publisher`.`id` and `book_features`.`featureid` = `features`.`id` and `book`.`categoryid` = $category and `book`.`subcategoryid` = $subcategory and `features`.`name` in ($features) and NULLIF(`book`.`deletedyn`,'') IS NULL group by `book`.`name`,`category`.`name`,`subcategory`.`name`,`publisher`.`name`) books order by `books`.`pid` LIMIT $count, 8";

		$result = mysqli_query($conn,$query);
	
		if ($result->num_rows > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				$names[] = array(
					'id' => $row['bookid'],
					'name' => $row['book_name'],
					'images' => $row['images'],		
					'publisher' => $row['publisher'],
					'desc' => $row['content'],
					'no_of_copies' => $row['no_of_copies'],
					'features' => $row['features']
				);
			}
			echo json_encode($names);
		}
		else{
			echo "<b>Feature not found</b>";
		}
	}

}
else if ($purpose == 'searchfeatures') {	
	$search = $_POST["query"];
	$features = $_POST["values"];
	$publishers = $_POST["publishervalues"];
	if($publishers != '' && $features != ''){
		$count = $_POST["count"];
		$count = $count * 8;
		$query = "SELECT `books`.`pid` as 'bookid',`book_name`,`books`.`publisher`,`books`.`features`,`books`.`images`,`publisherid`,`featureid` ,`books`.`content`,`books`.`no_of_copies` from (select `book`.`content`,`book`.`no_of_copies`,`publisher`.`id` as 'publisherid',`features`.`id` as 'featureid',`book`.`id` as 'pid',`book`.`name` as 'book_name',`publisher`.`name` as 'publisher',GROUP_CONCAT(`features`.`name`) as 'features',`book`.`images` as 'images' from `book`,`category`,`subcategory`,`publisher`,`features`, `book_features`where `book`.`id` = `book_features`.`bookid` and `book`.`categoryid` = `category`.`id` and `book`.`subcategoryid` = `subcategory`.`id` and `book`.`publisherid` = `publisher`.`id` and `book_features`.`featureid` = `features`.`id` and lower(`book`.`name`) LIKE '%$search%' and `features`.`name` in ($features) and  `publisher`.`name` in ($publishers) and NULLIF(`book`.`deletedyn`,'') IS NULL group by `book`.`name`,`category`.`name`,`subcategory`.`name`,`publisher`.`name`) books order by `books`.`pid` LIMIT $count, 8";

		$result = mysqli_query($conn,$query);
		
		if ($result->num_rows > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				$names[] = array(
					'id' => $row['bookid'],
					'name' => $row['book_name'],
					'images' => $row['images'],		
					'publisher' => $row['publisher'],
					'desc' => $row['content'],
					'no_of_copies' => $row['no_of_copies'],
					'features' => $row['features']
				);
			}
			echo json_encode($names);
		}
		else{
			echo "<b>Feature not found</b>";
		}
	}
	else if($publishers != '' && $features == ''){
		$count = $_POST["count"];
		$count = $count * 8;
		$query = "SELECT `books`.`pid` as 'bookid',`book_name`,`books`.`publisher`,`books`.`features`,`books`.`images`,`publisherid`,`featureid` ,`books`.`content`,`books`.`no_of_copies` from (select `book`.`content`,`book`.`no_of_copies`,`publisher`.`id` as 'publisherid',`features`.`id` as 'featureid',`book`.`id` as 'pid',`book`.`name` as 'book_name',`publisher`.`name` as 'publisher',GROUP_CONCAT(`features`.`name`) as 'features',`book`.`images` as 'images' from `book`,`category`,`subcategory`,`publisher`,`features`, `book_features`where `book`.`id` = `book_features`.`bookid` and `book`.`categoryid` = `category`.`id` and `book`.`subcategoryid` = `subcategory`.`id` and `book`.`publisherid` = `publisher`.`id` and `book_features`.`featureid` = `features`.`id` and lower(`book`.`name`) LIKE '%$search%' and `publisher`.`name` in ($publishers) and NULLIF(`book`.`deletedyn`,'') IS NULL group by `book`.`name`,`category`.`name`,`subcategory`.`name`,`publisher`.`name`) books order by `books`.`pid` LIMIT $count, 8";

		$result = mysqli_query($conn,$query);
		
		if ($result->num_rows > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				$names[] = array(
					'id' => $row['bookid'],
					'name' => $row['book_name'],
					'images' => $row['images'],		
					'publisher' => $row['publisher'],
					'desc' => $row['content'],
					'no_of_copies' => $row['no_of_copies'],
					'features' => $row['features']
				);
			}
			echo json_encode($names);
		}
		else{
			echo "<b>Feature not found</b>";
		}
	}
	else {
		$count = $_POST["count"];
		$count = $count * 8;
		$query = "SELECT `books`.`pid` as 'bookid',`book_name`,`books`.`publisher`,`books`.`features`,`books`.`images`,`publisherid`,`featureid` ,`books`.`content`,`books`.`no_of_copies` from (select `book`.`content`,`book`.`no_of_copies`, `publisher`.`id` as 'publisherid',`features`.`id` as 'featureid',`book`.`id` as 'pid',`book`.`name` as 'book_name',`publisher`.`name` as 'publisher',GROUP_CONCAT(`features`.`name`) as 'features',`book`.`images` as 'images' from `book`,`category`,`subcategory`,`publisher`,`features`, `book_features`where `book`.`id` = `book_features`.`bookid` and `book`.`categoryid` = `category`.`id` and `book`.`subcategoryid` = `subcategory`.`id` and `book`.`publisherid` = `publisher`.`id` and `book_features`.`featureid` = `features`.`id` and lower(`book`.`name`) LIKE '%$search%' and `features`.`name` in ($features) and NULLIF(`book`.`deletedyn`,'') IS NULL group by `book`.`name`,`category`.`name`,`subcategory`.`name`,`publisher`.`name`) books order by `books`.`pid` LIMIT $count, 8";

		$result = mysqli_query($conn,$query);
		
		if ($result->num_rows > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				$names[] = array(
					'id' => $row['bookid'],
					'name' => $row['book_name'],
					'images' => $row['images'],		
					'publisher' => $row['publisher'],
					'desc' => $row['content'],
					'no_of_copies' => $row['no_of_copies'],
					'features' => $row['features']
				);
			}
			echo json_encode($names);
		}
		else{
			echo "<b>Feature not found</b>";
		}
	}

}
else if ($purpose == 'getbook') {
	$category = $_POST["category"];	
	$subcategory = $_POST["subcategory"];
	$bookid = $_POST["book"];
	$username = $_POST["username"];

	$checkquery = "select `review`.`id` from `review`,`users`,`book` where NULLIF(`review`.`deletedyn`,'') IS NULL and `book`.`id` = `review`.`bookid` and `review`.`bookid` = $bookid and NULLIF(`book`.`deletedyn`,'') IS NULL";
	$checkresult = mysqli_query($conn,$checkquery);
	if ($checkresult->num_rows > 0) {
		while($row = mysqli_fetch_assoc($checkresult)) {
			$checkuser[] = array(
				'id' => $row['id']
			);
		}
	}
	
	if(count($checkuser) > 0){
		$query = "select `review`.`id`,`users`.`username`,`review`.`rating`,`review`.`feedback`,`review`.`datereviewed` from `review`,`users`,`book` where `users`.`id` = `review`.`userid` and NULLIF(`review`.`deletedyn`,'') IS NULL and `book`.`id` = `review`.`bookid` and `review`.`bookid` = $bookid and NULLIF(`book`.`deletedyn`,'') IS NULL and `users`.`username` = '$username' order by `review`.`datereviewed` desc";

		$result = mysqli_query($conn,$query);
		
		if ($result->num_rows > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				$currentuser[] = array(
					'id' => $row['id'],
					'username' => $row['username'],
					'review' => $row['feedback'],
					'rating' => $row['rating'],
					'date' => $row['datereviewed']
				);
			}
		}

		$query = "select `review`.`id`,`users`.`username`,`review`.`rating`,`review`.`feedback`,`review`.`datereviewed` from `review`,`users`,`book` where `users`.`id` = `review`.`userid` and NULLIF(`review`.`deletedyn`,'') IS NULL and `book`.`id` = `review`.`bookid` and `review`.`bookid` = $bookid and NULLIF(`book`.`deletedyn`,'') IS NULL and `users`.`username` != '$username' order by `review`.`datereviewed` desc";

		$result = mysqli_query($conn,$query);
	
		if ($result->num_rows > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				$names[] = array(
					'id' => $row['id'],
					'username' => $row['username'],
					'review' => $row['feedback'],
					'rating' => $row['rating'],
					'date' => $row['datereviewed']
				);
			}

			if(count($currentuser) > 0){
				$resultant = array_merge($currentuser,$names);
			}
			else if(count($names) < 0 && count($currentuser) > 0) {
				$resultant = $currentuser;
			}
			else {
				$resultant = $names;
			}

			$bookquery = "select `book`.`id`,`book`.`name`,`book`.`content`,`book`.`images`,`book`.`no_of_copies`,`publisher`.`name` as 'publisher', GROUP_CONCAT(`features`.`name`) as 'features',CAST(avg(`review`.`rating`) AS DECIMAL(10,1)) as 'average',`review2`.`total` as 'total' from `book`,`publisher`,`features`, `book_features`,`review`,(select count(*) as 'total' from  `book`,`review` where `book`.`id` = `review`.`bookid` and `book`.`id` = $bookid and NULLIF(`review`.`deletedyn`,'') IS NULL) `review2` where `book`.`id` = `book_features`.`bookid` and`book`.`publisherid` = `publisher`.`id` and `book_features`.`featureid` = `features`.`id` and NULLIF(`book`.`deletedyn`,'') IS NULL and `book`.`id` = $bookid and `book`.`id` = `review`.`bookid` group by `book`.`id`,`book`.`name`,`publisher`.`name`";

			$bookresult = mysqli_query($conn,$bookquery);
			if ($bookresult->num_rows > 0) {
				while($row = mysqli_fetch_assoc($bookresult)) {
					$book[] = array(	
						'id' => $row['id'],				
						'name' => $row['name'],
						'images' => $row['images'],
						'average' => $row['average'],
						'total' => $row['total'],
						'description' => $row['content'],
						'no_of_copies' => $row['no_of_copies'],
						'publisher' => $row['publisher'],
						'features' => $row['features'],
						'reviews' => $resultant
					);
				}
			}

			echo json_encode($book);
		}
		else{
			echo "<b>book not found</b>";
		}
	}
	else {
		$bookquery = "select `book`.`id`,`book`.`name`,`book`.`content`,`book`.`images`,`book`.`no_of_copies`,`publisher`.`name` as 'publisher', GROUP_CONCAT(`features`.`name`) as 'features' from `book`,`publisher`,`features`, `book_features`where `book`.`id` = `book_features`.`bookid` and`book`.`publisherid` = `publisher`.`id` and `book_features`.`featureid` = `features`.`id` and NULLIF(`book`.`deletedyn`,'') IS NULL and `book`.`id` = $bookid group by `book`.`id`,`book`.`name`,`publisher`.`name`";

			$bookresult = mysqli_query($conn,$bookquery);
			if ($bookresult->num_rows > 0) {
				while($row = mysqli_fetch_assoc($bookresult)) {
					$book[] = array(	
						'id' => $row['id'],				
						'name' => $row['name'],
						'images' => $row['images'],
						'average' => 0,
						'total' => 0,
						'description' => $row['content'],
						'no_of_copies' => $row['no_of_copies'],
						'publisher' => $row['publisher'],
						'features' => $row['features']
					);
				}
			}

			echo json_encode($book);

	}

}

else if ($purpose == 'addwishlist') {
	$bookid = $_POST["bookid"];
	$username = $_POST["username"];
	
	$query = "select * from `users`,`wishlist`,`book` where `users`.`username` = '$username' and `wishlist`.`userid` = `users`.`id` and NULLIF(`wishlist`.`deletedyn`,'') IS NULL and `book`.`id` = `wishlist`.`bookid` and `wishlist`.`bookid` = $bookid and NULLIF(`book`.`deletedyn`,'') IS NULL";
	$result = mysqli_query($conn,$query); 
	if ($result->num_rows > 0) {
		$final = 0;
    }
	else {
		$query = "insert into `wishlist`(`userid`,`bookid`) select `users`.`id`,$bookid from `users` where `users`.`username` = '$username'";
		$result = mysqli_query($conn,$query);
		if ($result) {
			$final = 1;
		}
	}
	echo $final;
}
else if ($purpose == 'addtocart') {
	$bookid = $_POST["bookid"];
	$username = $_POST["username"];
	
	$query = "select * from `users`,`cart`,`book` where `users`.`username` = '$username' and `cart`.`userid` = `users`.`id` and NULLIF(`cart`.`deletedyn`,'') IS NULL and `book`.`id` = `cart`.`bookid` and `cart`.`bookid` = $bookid and NULLIF(`book`.`deletedyn`,'') IS NULL";
	$result = mysqli_query($conn,$query); 
	if ($result->num_rows > 0) {
		$final = 0;
    }
	else {
		$query = "insert into `cart`(`userid`,`bookid`) select `users`.`id`,$bookid from `users` where `users`.`username` = '$username'";
		$result = mysqli_query($conn,$query);
		if ($result) {
			$final = 1;
		}
	}
	echo $final;
}

else if ($purpose == 'getcategoryandsubcategory') {
	$bookid = $_POST["bookid"];
	$username = $_POST["username"];

	$query = "select `categoryid`,`subcategoryid` from `book` where `book`.`id` = $bookid and NULLIF(`book`.`deletedyn`,'') IS NULL";
	$result = mysqli_query($conn,$query); 
	if ($result->num_rows > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			$names[] = array(
				'categoryid' => $row['categoryid'],
				'subcategoryid' => $row['subcategoryid']
			);
		}
		echo json_encode($names);
    }
}
else if ($purpose == 'pushreviews') {
	$bookid = $_POST["bookid"];
	$rate = $_POST["rating"];
	$username = $_POST["username"];
	$comment = $_POST["comment"];

	$query = "select `review`.`userid` from `users`,`review`,`book` where `users`.`username` = '$username' and `review`.`userid` = `users`.`id` and NULLIF(`review`.`deletedyn`,'') IS NULL and `book`.`id` = `review`.`bookid` and `review`.`bookid` = $bookid and NULLIF(`book`.`deletedyn`,'') IS NULL";
	$result = mysqli_query($conn,$query); 
	if ($result->num_rows > 0) {
		$query = "update `review` set `rating` = $rate,`feedback` = '$comment',`datereviewed` = DATE_FORMAT(now(), '%Y-%m-%d %H-%i-%s') where `review`.`bookid` = $bookid and `review`.`userid` in (select `users`.`id` from `users` where `users`.`username` = '$username')";
		$updateresult = mysqli_query($conn,$query);
		if($updateresult) {
        	$final = 'Updated';
        }
    }
	else {
		$query = "insert into `review`(`userid`,`bookid`,`rating`,`feedback`,`datereviewed`) select `users`.`id`,$bookid,$rate,'$comment',DATE_FORMAT(now(), '%Y-%m-%d %H-%i-%s') from `users` where `users`.`username` = '$username'";
		echo $query;
		$result = mysqli_query($conn,$query);
		if ($result) {
			$final = 'Inserted';
		}
	}
	echo $final;
}

else if ($purpose == 'updatereview') {
	$bookid = $_POST["bookid"];
	$username = $_POST["username"];
	$review = $_POST["review"];

	$query = "update `review` set `feedback` = '$review',`datereviewed` = DATE_FORMAT(now(), '%Y-%m-%d %H-%i-%s') where `review`.`bookid` = $bookid and `review`.`userid` in (select `users`.`id` from `users` where `users`.`username` = '$username') and NULLIF(`review`.`deletedyn`,'') IS NULL";

	$result = mysqli_query($conn,$query);
	if($result) {
      	echo 'Updated';
    }
}
else if ($purpose == 'getwishlist') {
	$username = $_POST["username"];

		$query = "select distinct `wishlist`.`bookid` from `users`,`wishlist`,`book` where `users`.`username` = '$username' and `wishlist`.`userid` = `users`.`id` and NULLIF(`wishlist`.`deletedyn`,'') IS NULL";
		$result = mysqli_query($conn,$query); 
	
	
		if($result->num_rows > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				$bookids[] = $row['bookid'];
			}
			$bookids = implode ( ', ', $bookids);
		
			$query = "SELECT `books`.`pid` as 'bookid',`book_name`,`books`.`publisher`,`books`.`features`,`books`.`images`,`publisherid`,`featureid`,`books`.`content`,`books`.`no_of_copies` from (select `book`.`content`,`book`.`no_of_copies`,`publisher`.`id` as 'publisherid',`features`.`id` as 'featureid',`book`.`id` as 'pid',`book`.`name` as 'book_name',`publisher`.`name` as 'publisher',GROUP_CONCAT(`features`.`name`) as 'features',`book`.`images` as 'images' from `book`,`category`,`subcategory`,`publisher`,`features`, `book_features`where `book`.`id` = `book_features`.`bookid` and `book`.`categoryid` = `category`.`id` and `book`.`subcategoryid` = `subcategory`.`id` and `book`.`publisherid` = `publisher`.`id` and `book_features`.`featureid` = `features`.`id` and `book`.`id` in ($bookids) and NULLIF(`book`.`deletedyn`,'') IS NULL group by `book`.`name`,`category`.`name`,`subcategory`.`name`,`publisher`.`name`) books order by `books`.`pid`";


			$result = mysqli_query($conn,$query);
	
			if ($result->num_rows > 0) {
				while($row = mysqli_fetch_assoc($result)) {
					$names[] = array(
						'id' => $row['bookid'],
						'name' => $row['book_name'],
						'images' => $row['images'],
						'rating' => $row['rating'],
						'recentreview' => $row['recentreview'],			
						'publisher' => $row['publisher'],
						'features' => $row['features'],
						'publisherid' => $row['publisherid'],
						'featureid' => $row['featureid']
					);
				}
				echo json_encode($names);
			}
			else{
				echo 0;
			}
    	}
    
    
}
else if ($purpose == 'getcart') {
	$username = $_POST["username"];

		$query = "select distinct `cart`.`bookid` from `users`,`cart`,`book` where `users`.`username` = '$username' and `cart`.`userid` = `users`.`id` and NULLIF(`cart`.`deletedyn`,'') IS NULL";
		$result = mysqli_query($conn,$query); 
	
	
		if($result->num_rows > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				$bookids[] = $row['bookid'];
			}
			$bookids = implode ( ', ', $bookids);
		
			$query = "SELECT `books`.`pid` as 'bookid',`book_name`,`books`.`publisher`,`books`.`features`,`books`.`images`,`publisherid`,`featureid`,`books`.`content`,`books`.`no_of_copies` from (select `book`.`content`,`book`.`no_of_copies`,`publisher`.`id` as 'publisherid',`features`.`id` as 'featureid',`book`.`id` as 'pid',`book`.`name` as 'book_name',`publisher`.`name` as 'publisher',GROUP_CONCAT(`features`.`name`) as 'features',`book`.`images` as 'images' from `book`,`category`,`subcategory`,`publisher`,`features`, `book_features`where `book`.`id` = `book_features`.`bookid` and `book`.`categoryid` = `category`.`id` and `book`.`subcategoryid` = `subcategory`.`id` and `book`.`publisherid` = `publisher`.`id` and `book_features`.`featureid` = `features`.`id` and `book`.`id` in ($bookids) and NULLIF(`book`.`deletedyn`,'') IS NULL group by `book`.`name`,`category`.`name`,`subcategory`.`name`,`publisher`.`name`) books order by `books`.`pid`";


			$result = mysqli_query($conn,$query);
	
			if ($result->num_rows > 0) {
				while($row = mysqli_fetch_assoc($result)) {
					$names[] = array(
						'id' => $row['bookid'],
						'name' => $row['book_name'],
						'images' => $row['images'],
						'rating' => $row['rating'],
						'recentreview' => $row['recentreview'],			
						'publisher' => $row['publisher'],
						'features' => $row['features'],
						'publisherid' => $row['publisherid'],
						'featureid' => $row['featureid']
					);
				}
				echo json_encode($names);
			}
			else{
				echo 0;
			}
    	}
    
    
}


else if ($purpose == 'removewish') {
	$username = $_POST["username"];
	$bookid = $_POST["bookid"];

	$query = "delete from wishlist where `wishlist`.`userid` in  (select `users`.`id` from `users` where `users`.`username` = '$username') and `wishlist`.`bookid` = $bookid";
	$result = mysqli_query($conn,$query); 
	
	if($result){
		echo true;
	}
	else{
		echo "<b>book not found</b>";
	}
}
else if ($purpose == 'removecart') {
	$username = $_POST["username"];
	$bookid = $_POST["bookid"];

	$query = "delete from cart where `cart`.`userid` in  (select `users`.`id` from `users` where `users`.`username` = '$username') and `cart`.`bookid` = $bookid";
	$result = mysqli_query($conn,$query); 
	
	if($result){
		echo true;
	}
	else{
		echo "<b>book not found</b>";
	}
}
else if ($purpose == 'deletereview') {	
	$bookid = $_POST["bookid"];
	$username = $_POST["username"];
	$review = $_POST["review"];

	$query = "update `review` set `deletedyn` = 'Y' where `review`.`bookid` = $bookid and `review`.`userid` in (select `users`.`id` from `users` where `users`.`username` = '$username') and NULLIF(`review`.`deletedyn`,'') IS NULL";

	$result = mysqli_query($conn,$query);

	if($result) {
      	echo 'Deleted';
    }
}
else if($purpose == 'addcategory') {
	$name = $_POST["name"];
	$image = $_POST["image"];
	$query = "INSERT INTO `category` (name,image) VALUES ('$name','$image')";
	$result = mysqli_query($conn,$query);
	if(! $result ) {
      die('Could not enter data: ' . mysql_error());
   }
   echo "Entered data successfully";
}
else if($purpose == 'deleteCategory') {
	$id = $_POST["id"];
	$query = "DELETE FROM `category` WHERE `category`.`id` = $id";
	$result = mysqli_query($conn,$query);
	if(! $result ) {
      die('Could not delete data: ' . mysql_error());
   }
   echo "Deleted data successfully";
}
else if($purpose == 'updatecategory') {
	$id = $_POST["id"];
	$name = $_POST["name"];
	$image = $_POST["image"];
	$query = "UPDATE `category` SET `name`='$name',`image`='$image' WHERE `category`.`id` = $id";
	$result = mysqli_query($conn,$query);
	if(! $result ) {
      die('Could not enter data: ' . mysql_error());
   }
   echo "Updated data successfully";
}
else if($purpose == 'deletesubcategory') {
	$id = $_POST["id"];
	$query = "DELETE FROM `subcategory` WHERE `subcategory`.`id` = $id";
	$result = mysqli_query($conn,$query);
	if(! $result ) {
      die('Could not delete data: ' . mysql_error());
   }
   echo "Deleted data successfully";
}
else if($purpose == 'updatesubcategory') {
	$id = $_POST["id"];
	$name = $_POST["name"];
	$image = $_POST["image"];
	$query = "UPDATE `subcategory` SET `name`='$name',`image`='$image' WHERE `subcategory`.`id` = $id";
	$result = mysqli_query($conn,$query);
	if(! $result ) {
      die('Could not enter data: ' . mysql_error());
   }
   echo "Updated data successfully";
}
else if ($purpose == 'addsubcategory') {
	$name = $_POST["name"];
	$category = $_POST["category"];
	$image = $_POST["image"];
	$query = "INSERT INTO `subcategory` (categoryid,name,image) VALUES ('$category','$name','$image')";
	$result = mysqli_query($conn,$query);
	if(! $result ) {
      die('Could not enter data: ' . mysql_error());
   }
   echo "Entered data successfully";
}
else  if ($purpose == 'addbook') {
	$name = $_POST["name"];
	$publisher = $_POST["publisher"];
	$no_of_copies = $_POST["no_of_copies"];
	$desc = $_POST["desc"];
	$feature = $_POST["feature"];
	$image = $_POST["image"];
	$category = $_POST["category"];
	$subcategory = $_POST["subcategory"];

	$query = "SELECT `id` from `publisher` where `name` = '$publisher'";
	$result = mysqli_query($conn,$query);
	if ($result->num_rows > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			$publisherid = $row['id'];
		}
	}else{
		$query = "INSERT INTO `publisher` (name) VALUES ('$publisher')";
		$result = mysqli_query($conn,$query);
		if(! $result ) {
			die('Could not enter data: ' . mysql_error());
		}
		$publisherid = $conn->insert_id;
	}

	$query = "SELECT `id` from `features` where `name` = '$feature'";
	$result = mysqli_query($conn,$query);
	if ($result->num_rows > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			$featureid = $row['id'];
		}
	}else{
		$query = "INSERT INTO `features` (name) VALUES ('$feature')";
		$result = mysqli_query($conn,$query);
		if(! $result ) {
			die('Could not enter data: ' . mysql_error());
		}
		$featureid = $conn->insert_id;
	}

	$query = "INSERT INTO `book` (name,categoryid,subcategoryid,publisherid,no_of_copies,date,content,images) VALUES ('$name',$category,$subcategory,$publisherid,$no_of_copies,CURDATE(),'$desc','$image')";
	$result = mysqli_query($conn,$query);
	if(! $result ) {
      die('Could not enter data: ' . mysql_error());
   	}
   	$bookid = $conn->insert_id;
   	$query = "INSERT INTO `book_features` (featureid,bookid) VALUES ($featureid,$bookid)";
	$result = mysqli_query($conn,$query);
	if(! $result ) {
      die('Could not enter data: ' . mysql_error());
   	}
   	echo "Entered data successfully";
}
else if($purpose == 'updatebook') {
	$name = $_POST["name"];
	$id = $_POST["id"];
	$publisher = $_POST["publisher"];
	$no_of_copies = $_POST["no_of_copies"];
	$desc = $_POST["desc"];
	$feature = $_POST["feature"];
	$image = $_POST["image"];
	$category = $_POST["category"];
	$subcategory = $_POST["subcategory"];

	$query = "SELECT `id` from `publisher` where `name` = '$publisher'";
	$result = mysqli_query($conn,$query);
	if ($result->num_rows > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			$publisherid = $row['id'];
		}
	}else{
		$query = "INSERT INTO `publisher` (name) VALUES ('$publisher')";
		$result = mysqli_query($conn,$query);
		if(! $result ) {
			die('Could not enter data: ' . mysql_error());
		}
		$publisherid = $conn->insert_id;
	}

	$query = "SELECT `id` from `features` where `name` = '$feature'";
	$result = mysqli_query($conn,$query);
	if ($result->num_rows > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			$featureid = $row['id'];
		}
	}else{
		$query = "INSERT INTO `features` (name) VALUES ('$feature')";
		$result = mysqli_query($conn,$query);
		if(! $result ) {
			die('Could not enter data: ' . mysql_error());
		}
		$featureid = $conn->insert_id;
	}
	$query = "UPDATE `book` SET `name`='$name',`publisherid`=$publisherid,`no_of_copies`=$no_of_copies,`content`='$desc',`images`='$image' WHERE `book`.`id` = $id";
	$result = mysqli_query($conn,$query);
	if(! $result ) {
      die('Could not enter data: ' . mysql_error());
   	}
   	echo "Updated data successfully";
}
else if($purpose == 'deletebook') {
	$id = $_POST["id"];
	$query = "DELETE FROM `book` WHERE `book`.`id` = $id";
	$result = mysqli_query($conn,$query);
	if(! $result ) {
      die('Could not delete data: ' . mysql_error());
   }
   echo "Deleted data successfully";
}
exit();	
?> 
