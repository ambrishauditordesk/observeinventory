<?php
    include 'dbconnection.php';
    include 'customMailer.php';

    if(!isset($_SESSION)){
       session_start();
    }
    $subject = "You have been invited for the documents upload";
    $loginLink = 'http://auditorsdesk.com/login';

    $wid = $_POST['wid'];
    $data = [];
    $result = $con->query("select user.email email, user.name name, accounts_log.id aid from user inner join accounts_log on accounts_log.client_contact_id=user.id where mail_send=0 and workspace_id=".$wid)->fetch_all();
    $date = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "d-m-Y H:m:s");
    $userEmail = $_SESSION['email'];

    $successEmailList = $unsuccessEmailList = '';
    $status = 0;
    $msg = '';
    foreach($result as $key => $value){
        $email = $value[0];
        $name = $value[1];
        $aid = $value[2];
        $msg = "<div>
         <div>Hello ".$name.",</div>
         <br />
         <div>This is a notification that your auditor has added items to their client request list and may need your
         attention. You can use the below link to access the client request page and respond to your auditor’s
         requests.</div>
         <br />
         <div>Your email id: ".$email."</div>
         <div>Login with your email id and password.</div>
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

        if(customMailer($email,$msg,$subject)){
            $status = 1;
            if(empty($successEmailList))
                $successEmailList = $email;
            else
                $successEmailList .= ','.$email;
            $con->query("update accounts_log set mail_send = 1 where id = $aid");
        }
        else{
            if(empty($unsuccessEmailList))
                $unsuccessEmailList = $email;
            else
                $unsuccessEmailList .= ','.$email;
        }
        
        $con->query("insert into activity_log(workspace_id, email, activity_date_time, activity_captured) values('$wid','$userEmail','$date','Email send successfully to $email for document upload.')");
        
        sleep(1);
    }
    $data['status'] = $status;
    $data['successEmailList'] = $successEmailList;
    $data['unsuccessEmailList'] = $unsuccessEmailList;
    echo json_encode($data);
?>