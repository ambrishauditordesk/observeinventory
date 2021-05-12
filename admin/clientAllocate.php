<?php
if(isset($_POST))
    {
        include '../dbconnection.php';
        session_start();

        $data['status'] = false;
        $data['text'] = 'Failed to allocate';

        $name = trim($_POST['name']);
        $client = $_POST['selectedValues'];
        $uid = $_POST['memberId'];
        $userRole = $con->query("select accessLevel from user where id = $uid")->fetch_assoc()['accessLevel'];
        if($userRole == 3 || $userRole == 2){
            $con->query("delete from user_client_log where user_id = '$uid'");
            foreach($client as $cid)
            {
                $result = $con->query("insert into user_client_log(client_id,user_id) values('$cid','$uid')");
            }
            $data = array();
            if($result)
            {
                $data['status'] = true;
                $data['text'] = 'Successfully Allocated';
            }
        }
        elseif($userRole == 4){
            $newClients = array();
            $oldClients = array();

            foreach($client as $cid)
            {
                $newClients[] = $cid;
            }

            if(!empty($newClients)){
                $clientList = $con->query("SELECT client.id id FROM user_client_log inner join user on user_client_log.user_id = user.id inner join firm_user_log on firm_user_log.user_id = user.id inner join role on user.accessLevel = role.id inner join client on user_client_log.client_id=client.id where user.accessLevel != 4 group by id");
                while($row = $clientList->fetch_assoc()){
                    $oldClients[] = $row['id'];
                }

                foreach($oldClients as $id){
                    $userList = $con->query("select user.id id from firm_user_log inner join user on firm_user_log.user_id = user.id where firm_user_log.firm_id in ( select firm_id from firm_user_log inner join firm_details on firm_details.id = firm_user_log.firm_id where firm_user_log.user_id = $uid ) and user.accessLevel != 4");
                    if (!in_array($id, $newClients)){
                        while($userRow = $userList->fetch_assoc()['id']){
                            if($con->query("select id from user_client_log where user_id = $userRow and client_id = $id")->num_rows){
                                $con->query("delete from user_client_log where user_id = $userRow and client_id = $id");
                                $data['status'] = true;
                            }
                        }
                        $con->query("delete from user_client_log where user_id = $uid and client_id = $id");
                    }
                }

                foreach($newClients as $id){
                    if(!in_array($id, $oldClients)){
                        if(!$con->query("select id from user_client_log where client_id = $id and user_id = $uid")->num_rows){
                            $con->query("insert into user_client_log(client_id,user_id) values('$id','$uid')");
                            $data['status'] = true;
                        }
                    }
                }

                if($data['status']){
                    $data['text'] = 'Successfully Allocated';
                }
            }
            else{
                $userList = $con->query("select user.id id from firm_user_log inner join user on firm_user_log.user_id = user.id where firm_user_log.firm_id in ( select firm_id from firm_user_log inner join firm_details on firm_details.id = firm_user_log.firm_id where firm_user_log.user_id = $uid )");
                while($row = $userList->fetch_assoc()['id']){
                    $con->query("delete from user_client_log where user_id = $row");
                    $data['status'] = true;
                    $data['text'] = 'Successfully Allocated';
                }
            }   
        }
        echo json_encode($data);
    }
?>