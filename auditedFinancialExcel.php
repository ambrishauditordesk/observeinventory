<?php
include 'dbconnection.php';
if(!isset($_SESSION)){
       session_start();
    }
$wid = $_SESSION['workspace_id'];
$clientId = $_SESSION['client_id'];
$htmlContent = '
<div>
   <div style="text-align:center">
      <h3>'.$con->query("SELECT concat(name, ' ', const) details FROM `client` inner join industry on client.industry_id = industry.id inner join constitution on client.const_id = constitution.id where client.id = $clientId")->fetch_assoc()['details'].'</h3>
      <h5><i>'.$con->query("SELECT concat(address,', ', city, ', ', state, '-', pincode,', ',country) address FROM `client` inner join industry on client.industry_id = industry.id inner join constitution on client.const_id = constitution.id where client.id = $clientId")->fetch_assoc()['address'].'</i></h3>
      <h5><i>'.$con->query("SELECT concat('Balance Sheet as on ', dateto) workspaceDate from workspace where client_id = $clientId")->fetch_assoc()['workspaceDate'].'</i></h3>
   </div>
   <br>
   <table class="table">
      <thead>
         <tr>
            <th>&nbsp;</th>
            <th>Particulars</th>
            <th>As on '.$con->query("SELECT datefrom from workspace where id = $wid")->fetch_assoc()['datefrom'].'</th>
            <th>As on '.$con->query("SELECT dateto from workspace where id = $wid")->fetch_assoc()['dateto'].'</th>
         </tr>
      </thead>
      <tbody>';
      $accountTypeResult = $con->query("SELECT DISTINCT accounts_type, accountTypeSeqNumber from tb_performance_map where workspace_id='$wid' and ( accounts_type not like '%Expense%' and accounts_type not like '%Revenue%' ) order by accountTypeSeqNumber");
      $typeCounter = 'A';
      while($accountTypeRow = $accountTypeResult->fetch_assoc()){
         $begBalTotal = $auditedTotal = 0;
         $htmlContent .= 
         '<tr>
            <td>('.$typeCounter++.')</td>
            <td>'.strtoupper($accountTypeRow['accounts_type']).'</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
         </tr>';
         $accountClassResult = $con->query("SELECT accounts_class from tb_performance_map where accounts_type ='".$accountTypeRow['accounts_type']."' and workspace_id='".$wid."' group by accounts_class");
         $accountClassCounter = 1;
         while($accountClassRow = $accountClassResult->fetch_assoc()){
            $htmlContent .= 
               '<tr>
               <td>'.$accountClassCounter++.'</td>
               <td>'.strtoupper($accountClassRow['accounts_class']).'</td>
               <td>&nbsp;</td>
               <td>&nbsp;</td>
            </tr>';
            $financialStatementResult = $con->query("SELECT accounts_name, sum(tb_performance_map.amount) unaudited, sum(tb_performance_map.beg_amount) beg_bal from tb_performance_map where workspace_id = $wid and accounts_type = '".$accountTypeRow['accounts_type']."' and accounts_class = '".$accountClassRow['accounts_class']."' GROUP BY accounts_name");
            $financialStatementCounter = 'a';
            while($financialStatementRow = $financialStatementResult->fetch_assoc()){
                $adjustment = $con->query("SELECT summery_of_misstatements_log.account, sum(summery_of_misstatements_log.amount) adj from summery_of_misstatements_log INNER join summery_of_misstatements on summery_of_misstatements_log.summery_of_misstatements_id=summery_of_misstatements.id where summery_of_misstatements.workspace_id = $wid and summery_of_misstatements_log.account = '".$financialStatementRow['accounts_name']."' GROUP BY summery_of_misstatements_log.account");
                $adjustment = $adjustment->num_rows > 0 ? $adjustment->fetch_assoc()['adj'] : 0;
                $audited = $financialStatementRow['unaudited']+$adjustment;
                $auditedTotal += $financialStatementRow['unaudited']+$adjustment;
                $begBalTotal += $financialStatementRow['beg_bal'];
               $htmlContent .= 
               '<tr>
                  <td>&nbsp;</td>
                  <td>('.$financialStatementCounter++.') '.$financialStatementRow['accounts_name'].'</td>
                  <td>'.($audited).'</td>
                  <td>'.($financialStatementRow['beg_bal']).'</td>
               </tr>';
            }
         }
         $htmlContent .= 
         '<tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
         </tr>
         <tr>
            <td>&nbsp;</td>
            <td>Total</td>
            <td>'.($auditedTotal).'</td>
            <td>'.($begBalTotal).'</td>
         </tr>
         <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
         </tr>';
      }
$htmlContent .= '</tbody></table></div>';
$htmlContent .= '<br style="page-break-before: always">';
$htmlContent .= '
<div>
   <div style="text-align:center">
      <h3>'.$con->query("SELECT concat(name, ' ', const) details FROM `client` inner join industry on client.industry_id = industry.id inner join constitution on client.const_id = constitution.id where client.id = $clientId")->fetch_assoc()['details'].'</h3>
      <h5><i>'.$con->query("SELECT concat(address,', ', city, ', ', state, '-', pincode,', ',country) address FROM `client` inner join industry on client.industry_id = industry.id inner join constitution on client.const_id = constitution.id where client.id = $clientId")->fetch_assoc()['address'].'</i></h3>
      <h5><i>'.$con->query("SELECT concat('Profit and Loss as on ', dateto) workspaceDate from workspace where client_id = $clientId")->fetch_assoc()['workspaceDate'].'</i></h3>
   </div>
   <br>
   <table class="table">
      <thead>
         <tr>
            <th>&nbsp;</th>
            <th>Particulars</th>
            <th>As on '.$con->query("SELECT datefrom from workspace where id = $wid")->fetch_assoc()['datefrom'].'</th>
            <th>As on '.$con->query("SELECT dateto from workspace where id = $wid")->fetch_assoc()['dateto'].'</th>
         </tr>
      </thead>
      <tbody>';
      $accountTypeResult = $con->query("SELECT DISTINCT accounts_type, accountTypeSeqNumber from tb_performance_map where workspace_id='$wid' and ( accounts_type like '%Expense%' or accounts_type like '%Revenue%' ) order by accountTypeSeqNumber");
      $typeCounter = 'A';
      while($accountTypeRow = $accountTypeResult->fetch_assoc()){
         $begBalTotal = $auditedTotal = 0;
         $htmlContent .= 
         '<tr>
            <td>('.$typeCounter++.')</td>
            <td>'.strtoupper($accountTypeRow['accounts_type']).'</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
         </tr>';
         $accountClassResult = $con->query("SELECT accounts_class from tb_performance_map where accounts_type ='".$accountTypeRow['accounts_type']."' and workspace_id='".$wid."' group by accounts_class");
         $accountClassCounter = 1;
         while($accountClassRow = $accountClassResult->fetch_assoc()){
            $htmlContent .= 
               '<tr>
               <td>'.$accountClassCounter++.'</td>
               <td>'.strtoupper($accountClassRow['accounts_class']).'</td>
               <td>&nbsp;</td>
               <td>&nbsp;</td>
            </tr>';
            $financialStatementResult = $con->query("SELECT accounts_name, sum(tb_performance_map.amount) unaudited, sum(tb_performance_map.beg_amount) beg_bal from tb_performance_map where workspace_id = $wid and accounts_type = '".$accountTypeRow['accounts_type']."' and accounts_class = '".$accountClassRow['accounts_class']."' GROUP BY accounts_name");
            $financialStatementCounter = 'a';
            while($financialStatementRow = $financialStatementResult->fetch_assoc()){
                  $adjustment = $con->query("SELECT summery_of_misstatements_log.account, sum(summery_of_misstatements_log.amount) adj from summery_of_misstatements_log INNER join summery_of_misstatements on summery_of_misstatements_log.summery_of_misstatements_id=summery_of_misstatements.id where summery_of_misstatements.workspace_id = $wid and summery_of_misstatements_log.account = '".$financialStatementRow['accounts_name']."' GROUP BY summery_of_misstatements_log.account");
                  $adjustment = $adjustment->num_rows > 0 ? $adjustment->fetch_assoc()['adj'] : 0;
                  $audited = $financialStatementRow['unaudited']+$adjustment;
                  $auditedTotal += $financialStatementRow['unaudited']+$adjustment;
                  $begBalTotal += $financialStatementRow['beg_bal'];
               $htmlContent .= 
               '<tr>
                  <td>&nbsp;</td>
                  <td>('.$financialStatementCounter++.') '.$financialStatementRow['accounts_name'].'</td>
                  <td>'.($audited).'</td>
                  <td>'.($financialStatementRow['beg_bal']).'</td>
               </tr>';
            }
         }
         $htmlContent .= 
         '<tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
         </tr>
         <tr>
            <td>&nbsp;</td>
            <td>Total</td>
            <td>'.($auditedTotal).'</td>
            <td>'.($begBalTotal).'</td>
         </tr>
         <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
         </tr>';
      }
       $htmlContent .= '
       </tbody>
   </table>
</div>';
header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment; filename=Audited Financial Statement.xls");
echo $htmlContent;
?>