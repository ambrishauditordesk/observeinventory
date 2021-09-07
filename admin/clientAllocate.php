<?php
if(isset($_POST))
    {
        include '../dbconnection.php';
        include '../customMailer.php';
        session_start();

        $data['status'] = false;
        $data['text'] = 'Failed to allocate';
        $mailSend = 0;
        $clientNameList = '';

        $name = trim($_POST['name']);
        $client = $_POST['selectedValues'];
        $uid = $_POST['memberId'];
        $userRole = $con->query("select accessLevel from user where id = $uid")->fetch_assoc()['accessLevel'];
        if($userRole == 3 || $userRole == 2){
            $newClients = array();
            $oldClients = array();

            foreach($client as $cid)
            {
                $newClients[] = $cid;
            }
            if(!empty($newClients)){
                $clientList = $con->query("SELECT client_id id FROM user_client_log where user_id = $uid");
                while($row = $clientList->fetch_assoc()){
                    $oldClients[] = $row['id'];
                }

                foreach($oldClients as $id){
                    if (!in_array($id, $newClients)){
                        $con->query("delete from user_client_log where id = $id");
                        $data['status'] = true;
                    }
                }

                foreach($newClients as $id){
                    if(!in_array($id, $oldClients)){
                        $con->query("insert into user_client_log(client_id,user_id) values('$id','$uid')");
                        $clientName = $con->query("select name from client where id = $id")->fetch_assoc()['name'];
                        $clientNameList .= $clientNameList == ''? $clientName:', '.$clientName;
                        $mailSend = 1;
                        $data['status'] = true;
                    }
                }

                if($data['status']){
                    $data['text'] = 'Successfully Allocated';
                }
            }
            else{
                $userList = $con->query("select id from user_client_log where user_id = $uid ");
                while($row = $userList->fetch_assoc()['id']){
                    $con->query("delete from user_client_log where id = $row");
                    $data['status'] = true;
                    $data['text'] = 'Successfully Allocated';
                }
            }
        }
        if($userRole == 4){
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
                            $clientName = $con->query("select name from client where id = $uid")->fetch_assoc()['name'];
                            $clientNameList .= $clientNameList == ''? $clientName:', '.$clientName;
                            $mailSend = 1;
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

        if($mailSend){
            $memberDetails = $con->query("select name, email from user where id = $uid")->fetch_assoc();
            $sub = "You have been added as a active member";
            $name = $memberDetails['name'];
            $email = $memberDetails['email'];
            $loginLink = 'http://auditorsdesk.com/AuditSoft/login';
             
            $msg = "<div>
                <div>Hello ".$name.",</div>
                <br />
                <div>You have been added as a active member to join Digital audit workspace by your firm administrator. Use
                your user id to login to the workspace you have been allocated to.</div>
                <br />
                <div>Clients allocated are:- ".$clientNameList."</div>
                <br />
                <div>Your email id: ".$email."</div>
                <br/>
                <a href='".$loginLink."'><button style=' background-color: #008CBA; border: none; color: white; padding: 15px 32px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; cursor:pointer;'>Login</button></a>
                <br />
                <br />
                <div>Note:- For security purposes, please do not share this email with anyone as it contains your account</div>
                <div>information. If you have login problems or questions, or you are having problems with this email, please</div>
                <div>contact the Help desk or your firm administrator.</div>
                <br />
                <div>Thank you.</div>
                <br />
                <div>Auditor's Desk Team</div>
                </div>";
                customMailer($email,$msg,$sub);
        }
        echo json_encode($data);
    }
?>