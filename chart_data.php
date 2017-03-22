<?php
//address of the server where db is installed
$servername = "localhost";

//username to connect to the db
//the default value is root
$username = "root";

//password to connect to the db
//this is the value you would have specified during installation of WAMP stack
$password = "";

//name of the db under which the table is created
$dbName = "db1";

//establishing the connection to the db.
$conn = new mysqli($servername, $username, $password, $dbName);

//checking if there were any error during the last connection attempt
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

	$first_day_of_month=date('Y-m-01');
	$startdate=$first_day_of_month; 
        $today=date('Y-m-d');
	$enddate=$today;
	$date_cnd="and si.created_date<='$enddate' and si.created_date>='$startdate'";
	$contact_cnd="";
//the SQL query to be executed
$query = "select count(*) as counter,si.first_name,si.last_name,si.phone,si.city,si.email,a.appointment_date_time,si.student_contact_us from student_inquiry si ,appointment a where a.id = si.inquiry_id and a.table_name = 'student_inquiry' ".$date_cnd." ". $contact_cnd." GROUP BY student_contact_us ";

//storing the result of the executed query
$result = $conn->query($query);

//initialize the array to store the processed data
$jsonArray = array();

//check if there is any data returned by the SQL Query
if ($result->num_rows > 0) {
  //Converting the results into an associative array
  while($row = $result->fetch_assoc()) {
    $jsonArrayItem = array();
    $jsonArrayItem['label'] = $row['student_contact_us'];
    $jsonArrayItem['value'] = $row['counter'];
    //append the above created object into the main array.
    array_push($jsonArray, $jsonArrayItem);
  }
}

//Closing the connection to DB
$conn->close();

//set the response content type as JSON
header('Content-type: application/json');
//output the return value of json encode using the echo function. 
echo json_encode($jsonArray);
?>