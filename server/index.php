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

if ($purpose == 'getTopbooks') {
    $query = "select avg(`rating`) as toprated,`book`.`name`,`book`.`id`,`book`.`categoryid`,`book`.`subcategoryid`,`book`.`images` from `review`,`book` where `review`.`bookid` = `book`.`id` group by `book`.`name`,`book`.`content` order by toprated desc limit 10";
	$result = mysqli_query($conn,$query);
	if ($result->num_rows > 0) {		
		while($row = mysqli_fetch_assoc($result)) {
			$names[] = array(			
				'name' => $row['name'],
				'id' => $row['id'],
				'category' => $row['categoryid'],
				'subcategory' => $row['subcategoryid'],
				'image' => $row['images']
			);
		}
		echo json_encode($names);
	}else{
		echo "No top books";
	}
}
if ($purpose == 'getPopularCategories') {
    $query = "SELECT category.`name`,category.`image`,`category`.`id`,count(review.`id`) as `review_count` from category,`book`,`review` where category.`id` = book.`categoryid` and review.`bookid` = book.`id` group by category.`name`,`category`.`id` order by count(review.`id`) desc limit 10";
	$result = mysqli_query($conn,$query);
	if ($result->num_rows > 0) {		
		while($row = mysqli_fetch_assoc($result)) {
			$names[] = array(			
				'name' => $row['name'],
				'id' => $row['id'],
				'image' => $row['image'],
				'review_count' => $row['review_count']
			);
		}
		echo json_encode($names);
	}else{
		echo "No top books";
	}
}

exit();	
?> 