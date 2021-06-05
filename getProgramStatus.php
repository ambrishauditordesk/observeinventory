<?php
function getProgramStatus($id, $wid){
    
    include 'dbconnection.php';
    
    $data = array();
    
    $data['totalCount'] = $data['statusCount'] = $totalStatusCount = 0;
    
    if($id != 12){
        $result = $con->query("SELECT program.id id FROM program inner join workspace_log on workspace_log.program_id = program.id WHERE program.parent_id = $id and hasChild = 0 and workspace_log.workspace_id = $wid and import = 1 UNION SELECT program.id id FROM program inner join workspace_log on workspace_log.program_id = program.id WHERE program.parent_id IN (SELECT id FROM program WHERE program.parent_id = $id) and hasChild = 0 and workspace_log.workspace_id = $wid and import = 1");
        if($result->num_rows != 0){
            $totalCount = $result->num_rows;
            while($row = $result->fetch_assoc()){
                $status = $con->query("select status from workspace_log where program_id = ".$row['id'])->fetch_assoc()['status'];
                $totalStatusCount += $status;
            }
            $data['totalCount'] = $totalCount;
            $data['statusCount'] = $totalStatusCount;
        }
    }
    else{
        $data['totalCount'] = (int)$con->query("SELECT count(id) total from materiality where workspace_id = $wid")->fetch_assoc()['total'];
        $data['statusCount'] = (int)$con->query("SELECT count(id) total FROM materiality where workspace_id = $wid and ( standard_low != '' or standard_high != '' or custom != '' or amount != '' )")->fetch_assoc()['total'];

        $data['totalCount'] += (int)$con->query("SELECT count(id) total FROM workspace_log where ( program_id >= 35 and program_id <= 46 ) or ( program_id >= 231 and program_id <= 237 ) and workspace_id = $wid")->fetch_assoc()['total'];
        $data['statusCount'] += (int)$con->query("SELECT count(id) total FROM workspace_log where ( program_id >= 35 or program_id <= 46 ) and ( program_id >= 231 or program_id <= 237 ) and workspace_id = $wid and amount != ''")->fetch_assoc()['total'];

    }
    
    return $data;
}
?>