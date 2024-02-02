<?php
include('dbcon.php');
$draw = $_POST['draw'];  
$row = $_POST['start'];
$rowperpage = $_POST['length']; 
$columnIndex = $_POST['order'][0]['column']; 
$columnName = $_POST['columns'][$columnIndex]['data']; 
$columnSortOrder = $_POST['order'][0]['dir']; 
$searchValue = mysqli_real_escape_string($conn,$_POST['search']['value']); 
  
$searchQuery = " ";
if($searchValue != ''){
   $searchQuery .= " and (name like '%".$searchValue."%' or
            position like '%".$searchValue."%' or
            office like'%".$searchValue."%' ) ";
}
 
$sel = mysqli_query($conn,"select count(*) as allcount from employee");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];
 
$sel = mysqli_query($conn,"select count(*) as allcount from employee WHERE 1 ".$searchQuery);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];
 
$empQuery = "select * from employee WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn, $empQuery);
 
$data = array();
 
while($row = mysqli_fetch_assoc($empRecords)){
    $salary = $row['salary'];
    $salaryarray = " $salary";
    $data[] = array(
            "name"=>$row['name'],
            "position"=>$row['position'],
            "age"=>$row['age'],
            "salary"=>$salaryarray,
            "office"=>$row['office']
        );
}
 
$response = array(
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);
 
echo json_encode($response);
 
?>