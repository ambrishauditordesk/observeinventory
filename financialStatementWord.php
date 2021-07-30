<?php
include_once 'HtmlToDoc.class.php';  
include 'dbconnection.php';
include 'moneyFormatterFPDF.php';
session_start();
$wid = $_SESSION['workspace_id'];
$clientId = $_SESSION['client_id'];
// Initialize class 
$htd = new HTML_TO_DOC();
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
      $accountTypeResult = $con->query("SELECT DISTINCT account_type, accountTypeSeqNumber from trial_balance where workspace_id='$wid' and ( account_type not like '%Expense%' and account_type not like '%Revenue%' ) order by accountTypeSeqNumber");
      $typeCounter = 'A';
       while($accountTypeRow = $accountTypeResult->fetch_assoc()){
         $cyBegBalTotal = $cyFinalBalTotal = 0;
         $htmlContent .= 
         '<tr>
            <td>('.$typeCounter++.')</td>
            <td>'.strtoupper($accountTypeRow['account_type']).'</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
         </tr>';
         $accountClassResult = $con->query("SELECT account_class from trial_balance where account_type ='".$accountTypeRow['account_type']."' and workspace_id='".$wid."' group by account_class");
         $accountClassCounter = 1;
         while($accountClassRow = $accountClassResult->fetch_assoc()){
            $htmlContent .= 
               '<tr>
               <td>'.$accountClassCounter++.'</td>
               <td>'.strtoupper($accountClassRow['account_class']).'</td>
               <td>&nbsp;</td>
               <td>&nbsp;</td>
            </tr>';
            $financialStatementResult = $con->query("SELECT max(financial_statement) financial_statement, sum(cy_beg_bal) cy_beg_bal, sum(cy_final_bal) cy_final_bal from trial_balance where account_type ='".$accountTypeRow['account_type']."' and account_class ='".$accountClassRow['account_class']."' and workspace_id='".$wid."' group by account_class,account_class,financial_statement order by financial_statement");
            $financialStatementCounter = 'a';
            while($financialStatementRow = $financialStatementResult->fetch_assoc()){
               $cyFinalBalTotal += $financialStatementRow['cy_final_bal'];
               $cyBegBalTotal += $financialStatementRow['cy_beg_bal'];
               $htmlContent .= 
               '<tr>
                  <td>&nbsp;</td>
                  <td>('.$financialStatementCounter++.') '.$financialStatementRow['financial_statement'].'</td>
                  <td>'.numberToCurrency($financialStatementRow['cy_final_bal']).'</td>
                  <td>'.numberToCurrency($financialStatementRow['cy_beg_bal']).'</td>
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
            <td>'.numberToCurrency($cyFinalBalTotal).'</td>
            <td>'.numberToCurrency($cyBegBalTotal).'</td>
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
      $accountTypeResult = $con->query("SELECT DISTINCT account_type, accountTypeSeqNumber from trial_balance where workspace_id='$wid' and ( account_type like '%Expense%' or account_type like '%Revenue%' ) order by accountTypeSeqNumber");
      $typeCounter = 'A';
       while($accountTypeRow = $accountTypeResult->fetch_assoc()){
         $cyBegBalTotal = $cyFinalBalTotal = 0;
         $htmlContent .= 
         '<tr>
            <td>('.$typeCounter++.')</td>
            <td>'.strtoupper($accountTypeRow['account_type']).'</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
         </tr>';
         $accountClassResult = $con->query("SELECT account_class from trial_balance where account_type ='".$accountTypeRow['account_type']."' and workspace_id='".$wid."' group by account_class");
         $accountClassCounter = 1;
         while($accountClassRow = $accountClassResult->fetch_assoc()){
            $htmlContent .= 
               '<tr>
               <td>'.$accountClassCounter++.'</td>
               <td>'.strtoupper($accountClassRow['account_class']).'</td>
               <td>&nbsp;</td>
               <td>&nbsp;</td>
            </tr>';
            $financialStatementResult = $con->query("SELECT max(financial_statement) financial_statement, sum(cy_beg_bal) cy_beg_bal, sum(cy_final_bal) cy_final_bal from trial_balance where account_type ='".$accountTypeRow['account_type']."' and account_class ='".$accountClassRow['account_class']."' and workspace_id='".$wid."' group by account_class,account_class,financial_statement order by financial_statement");
            $financialStatementCounter = 'a';
            while($financialStatementRow = $financialStatementResult->fetch_assoc()){
               $cyFinalBalTotal += $financialStatementRow['cy_final_bal'];
               $cyBegBalTotal += $financialStatementRow['cy_beg_bal'];
               $htmlContent .= 
               '<tr>
                  <td>&nbsp;</td>
                  <td>('.$financialStatementCounter++.') '.$financialStatementRow['financial_statement'].'</td>
                  <td>'.numberToCurrency($financialStatementRow['cy_final_bal']).'</td>
                  <td>'.numberToCurrency($financialStatementRow['cy_beg_bal']).'</td>
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
            <td>'.numberToCurrency($cyFinalBalTotal).'</td>
            <td>'.numberToCurrency($cyBegBalTotal).'</td>
         </tr>
         <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
         </tr>';
       }
       $htmlContent .= '</tbody></table></div>';
$htd->createDoc($htmlContent, "Financial Statement.doc", 1);
?>