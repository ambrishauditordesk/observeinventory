<?php
function getProgramStatus($id, $wid){
    if (!isset($_SESSION))
        if(!isset($_SESSION)){
       session_start();
    }
    include 'dbconnection.php';
    
    $data = array();
    
    $data['totalCount'] = $data['statusCount'] = $totalStatusCount = 0;

    $query = "SELECT workspace_log.id id FROM program inner join workspace_log on workspace_log.program_id = program.id WHERE program.parent_id = $id and hasChild = 0 and workspace_log.workspace_id = $wid and import = 1 UNION SELECT workspace_log.id id FROM program inner join workspace_log on workspace_log.program_id = program.id WHERE program.parent_id IN (SELECT id FROM program WHERE program.parent_id = $id) and hasChild = 0 and workspace_log.workspace_id = $wid and import = 1";
    
    if($_SESSION['firm_details']['plan'] == 1){
        $query = "SELECT workspace_log.id id FROM program inner join workspace_log on workspace_log.program_id = program.id WHERE program.parent_id = $id and hasChild = 0 and workspace_log.workspace_id = $wid and import = 1 and program.    firmPlan = 0 UNION SELECT workspace_log.id id FROM program inner join workspace_log on workspace_log.program_id = program.id WHERE program.parent_id IN (SELECT id FROM program WHERE program.parent_id = $id) and hasChild = 0 and workspace_log.workspace_id = $wid and import = 1  and program.firmPlan = 0";
    }
    
    if($id != 12){
        $result = $con->query($query);
        if($result->num_rows != 0){
            $totalCount = $result->num_rows;
            while($row = $result->fetch_assoc()){
                $status = $con->query("select status from workspace_log where workspace_id = $wid and id = ".$row['id'])->fetch_assoc()['status'];
                $totalStatusCount += $status;
            }
            $data['totalCount'] = $totalCount;
            $data['statusCount'] = $totalStatusCount;
            $data['query'] = $query;
        }
    }
    else{

        $data['totalCount'] = (int)$con->query("SELECT count(id) total from materiality where workspace_id = $wid")->fetch_assoc()['total'];
        $data['statusCount'] = (int)$con->query("SELECT count(id) total FROM materiality where workspace_id = $wid and ( standard_low != '' or standard_high != '' or custom != '' or amount != '' )")->fetch_assoc()['total'];

        $data['totalCount'] += (int)$con->query("SELECT count(id) total FROM tb_performance_map where workspace_id = $wid")->fetch_assoc()['total'];
        $data['statusCount'] += (int)$con->query("SELECT count(id) total FROM tb_performance_map where amount != '' and workspace_id = $wid")->fetch_assoc()['total'];

    }
    
    return $data;
}
?>