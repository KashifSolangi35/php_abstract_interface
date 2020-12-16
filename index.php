<?php
require_once('staff.php');
$staff_obj = new Staff;

$where = "";
//$where = "name_code='KMS'";
$where = "is_active=1";

$staff_res = $staff_obj->getData($where);
//var_dump($staff_res); 
//die;

$html = "";
$html .= '<!DOCTYPE html>';
$html .= '<html lang="en">';
$html .= '<head>';
$html .= '<title>Interfaces vs. Abstract Classes in PHP</title>';
$html .= '<meta charset="utf-8">';
$html .= '<meta name="viewport" content="width=device-width, initial-scale=1">';
$html .= '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">';
$html .= '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>';
$html .= '<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>';
$html .= '</head>';
$html .= '<body>';

$html .= '<div class="container">';
  
if($staff_res['res']=== 200 || 204){
    $html .= "<h1> Staff Information </h1>";
    $html .= "<table class='table table-bordered'>";
    $html .= "<thead>";
    $html .= "<tr>";
    $html .= "<th scope='col'> Abridged name</th>";
    $html .= "<th scope='col'> Name Code </th>";
    $html .= "</thead>";
    $html .= "</tr>";

    $html .= "<tbody>";
    if(isset($staff_res['dataArray'])){
        foreach( $staff_res['dataArray'] as $result){
            
            $html .= "<tr>";
                $html .= "<td>"; 
                    $html .= $result["abridged_name"]; 
                $html .= "</td>";
                $html .= "<td>"; 
                    $html .= $result["name_code"]; 
                $html .= "</td>";
            $html .= "</tr>";
        }
    }else {
        $html .= "<tr> <td colspan='2'>No staff found. </td></tr>";

    }
} else {
    $html .= "Unable to display results";
}

$html .= "</tbody>";
$html .= '</div>';
$html .= '</body>';
$html .= '</html> ';

echo $html;