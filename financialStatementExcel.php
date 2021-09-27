<?php
   include 'dbconnection.php';
   session_start();
   $wid = $_SESSION['workspace_id'];
   $clientId = $_SESSION['client_id'];
   $constitution = $con->query("SELECT const_id from client where id = $clientId")->fetch_assoc()['const_id'];
   $htmlContent = '
   <div style="text-align:center">
      <h3>'.$con->query("SELECT concat(name, ' ', const) details FROM `client` inner join industry on client.industry_id = industry.id inner join constitution on client.const_id = constitution.id where client.id = $clientId")->fetch_assoc()['details'].'</h3>
      <h5><i>'.$con->query("SELECT concat(address,', ', city, ', ', state, '-', pincode,', ',country) address FROM `client` inner join industry on client.industry_id = industry.id inner join constitution on client.const_id = constitution.id where client.id = $clientId")->fetch_assoc()['address'].'</i></h3>
      <h5><i>'.$con->query("SELECT concat('Balance Sheet as on ', dateto) workspaceDate from workspace where client_id = $clientId")->fetch_assoc()['workspaceDate'].'</i></h3>
   </div>
   <br>';
   if($constitution == 2){
      $htmlContent .= '
      <div>
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
                        <td>'.($financialStatementRow['cy_final_bal']).'</td>
                        <td>'.($financialStatementRow['cy_beg_bal']).'</td>
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
                  <td>'.($cyFinalBalTotal).'</td>
                  <td>'.($cyBegBalTotal).'</td>
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
                        <td>'.($financialStatementRow['cy_final_bal']).'</td>
                        <td>'.($financialStatementRow['cy_beg_bal']).'</td>
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
                  <td>'.($cyFinalBalTotal).'</td>
                  <td>'.($cyBegBalTotal).'</td>
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
   }
   else{
      $htmlContent .= '
      <div>
         <table class="table">
            <thead>
               <tr>
                  <th>&nbsp;</th>
                  <th>Particulars</th>
                  <th>'.$con->query("SELECT datefrom from workspace where id = $wid")->fetch_assoc()['datefrom'].'</th>
                  <th>Particulars</th>
                  <th>'.$con->query("SELECT dateto from workspace where id = $wid")->fetch_assoc()['dateto'].'</th>
               </tr>
            </thead>
            <tbody>';
      $arrayOfAssets = $con->query("SELECT distinct account_class from trial_balance where workspace_id = $wid and account_type like '%Asset%'");
      $i = 0;
      $finalArrayOfAssets = []; 
      while($arrayOfAssetsRow = $arrayOfAssets->fetch_assoc()){
         $arrayOfAssetsResult = $con->query("SELECT max(financial_statement) financial_statement, sum(cy_final_bal) total FROM trial_balance where account_class = '".$arrayOfAssetsRow['account_class']."' GROUP BY financial_statment");
         while($row = $arrayOfAssetsResult->fetch_assoc()){
            $finalArrayOfAssets[$i][0] = $row['financial_statement'];
            $finalArrayOfAssets[$i++][1] = $row['total'];
         }
      }
      
      $arrayOfLiabilities = $con->query("SELECT distinct account_class from trial_balance where workspace_id = $wid and account_type like '%Liabilities%'");
      $i = 0;
      $finalArrayOfLiabilities = []; 
      while($arrayOfLiabilitiesRow = $arrayOfLiabilities->fetch_assoc()){
         $arrayOfLiabilitiesResult = $con->query("SELECT max(financial_statement) financial_statement, sum(cy_final_bal) total FROM trial_balance where account_class = '".$arrayOfLiabilitiesRow['account_class']."' GROUP BY financial_statment");
         while($row = $arrayOfLiabilitiesResult->fetch_assoc()){
            $finalArrayOfLiabilities[$i][0] = $row['financial_statement'];
            $finalArrayOfLiabilities[$i++][1] = $row['total'];
         }
      }

      $arrayOfOthers = $con->query("SELECT distinct account_class from trial_balance where workspace_id = $wid and ( account_type not like '%Asset%' and account_type not like '%Liabilities%' and account_type not like '%Expenses%' and account_type not like '%Sales%' and account_type not like '%Income%')");
      while($arrayOfOthersRow = $arrayOfOthers->fetch_assoc()){
         $arrayOfOthersResult = $con->query("SELECT max(financial_statement) financial_statement, sum(cy_final_bal) total FROM trial_balance where account_class = '".$arrayOfSalesRow['account_class']."' GROUP BY financial_statment");
         while($row = $arrayOfOthersResult->fetch_assoc()){
            $finalArrayOfLiabilities[$i][0] = $row['financial_statement'];
            $finalArrayOfLiabilities[$i++][1] = $row['total'];
         }
      }

      $maxIteration = count($arrayOfAssests);
      $totalLiabilities = $totalAssets = $lineCount = 0;
      for($i=0;$i<$maxIteration;$i++) {
         if(isset($finalArrayOfLiabilities[$i])) {
            $liabilityName = $finalArrayOfLiabilities[$i][0];
            $amount = $finalArrayOfLiabilities[$i][1];
            $totalLiabilities += $amount;
         }
         else {
            $liabilityName = '';
            $amount = '';
         }
         $htmlContent .= 
            '<tr>
               <td>'.$liabilityName.'</td>
               <td>'.($amount).'</td>
               <td>'.$finalArrayOfAssets[$i][0].'</td>
               <td>'.($finalArrayOfAssets[$i][1]).'</td>
            </tr>';
      }

      $htmlContent .= 
            '<tr>
               <td>TOTAL</td>
               <td>'.($totalLiabilities).'</td>
               <td>TOTAL</td>
               <td>'.($totalAssets).'</td>
            </tr>';
            
      $htmlContent .= '
            </tbody>
         </table>
      </div>';
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
                  <th>Particulars</th>
                  <th>'.$con->query("SELECT datefrom from workspace where id = $wid")->fetch_assoc()['datefrom'].'</th>
                  <th>Particulars</th>
                  <th>'.$con->query("SELECT dateto from workspace where id = $wid")->fetch_assoc()['dateto'].'</th>
               </tr>
            </thead>
            <tbody>';
      
      $arrayOfSales = $con->query("SELECT distinct account_class from trial_balance where workspace_id = $wid and account_type like '%Sales%'");
      $i = 0;
      $finalArrayOfSales = []; 
      while($arrayOfSalesRow = $arrayOfSales->fetch_assoc()){
         $arrayOfSalesResult = $con->query("SELECT max(financial_statement) financial_statement, sum(cy_final_bal) total FROM trial_balance where account_class = '".$arrayOfSalesRow['account_class']."' GROUP BY financial_statment");
         while($row = $arrayOfSalesResult->fetch_assoc()){
            $finalArrayOfSales[$i][0] = $row['financial_statement'];
            $finalArrayOfSales[$i++][1] = $row['total'];
         }
      }


      $arrayOfPurchase = $con->query("SELECT distinct account_class from trial_balance where workspace_id = $wid and account_type like '%Purchase%'");
      $i = 0;
      $finalArrayOfPurchase = []; 
      while($arrayOfPurchaseRow = $arrayOfPurchase->fetch_assoc()){
         $arrayOfPurchaseResult = $con->query("SELECT max(financial_statement) financial_statement, sum(cy_final_bal) total FROM trial_balance where account_class = '".$arrayOfSalesRow['account_class']."' GROUP BY financial_statment");
         while($row = $arrayOfPurchaseResult->fetch_assoc()){
            $finalArrayOfPurchase[$i][0] = $row['financial_statement'];
            $finalArrayOfPurchase[$i++][1] = $row['total'];
         }
      }

      $maxIteration = count($finalArrayOfPurchase) > count($finalArrayOfSales) ? count($finalArrayOfPurchase) : count($finalArrayOfSales);

      $totalAmountForPurchase = $totalAmountForSales = 0;

      for($i=0;$i<$maxIteration;$i++) {
         
         if(isset($finalArrayOfSales[$i])) {
            $name = $finalArrayOfSales[$i][0];
            $amount = $finalArrayOfSales[$i][1];
            $totalAmountForSales += $amount; //amount field
         }
         else {
            $name = '';
            $amount = '';
         }

         $totalAmountForPurchase += $finalArrayOfPurchase[$i][1];

         $htmlContent .= 
            '<tr>
               <td>'.$finalArrayOfPurchase[$i][0].'</td>
               <td>'.$finalArrayOfPurchase[$i][1].'</td>
               <td>'.$name.'</td>
               <td>'.($amount).'</td>
            </tr>';
      }
      //gross profit
      $grossTotal = $totalAmountForSales - $totalAmountForPurchase;

      $htmlContent .= 
            '<tr>
               <td>Gross Profit</td>
               <td>'.($grossTotal).'</td>
               <td>&nbsp;</td>
               <td>&nbsp;</td>
            </tr>
            <tr>
               <td>TOTAL</td>
               <td>'.($totalAmountForPurchase).'</td>
               <td>TOTAL</td>
               <td>'.($totalAmountForSales).'</td>
            </tr>';

      $arrayOfExpenses = $con->query("SELECT distinct account_class from trial_balance where workspace_id = $wid and account_type like '%Expenses%'");
      $i = 0;
      $finalArrayOfExpenses = []; 
      while($arrayOfExpensesRow = $arrayOfExpenses->fetch_assoc()){
         $arrayOfExpensesResult = $con->query("SELECT max(financial_statement) financial_statement, sum(cy_final_bal) total FROM trial_balance where account_class = '".$arrayOfExpensesRow['account_class']."' GROUP BY financial_statment");
         while($row = $arrayOfExpensesResult->fetch_assoc()){
            $finalArrayOfExpenses[$i][0] = $row['financial_statement'];
            $finalArrayOfExpenses[$i++][1] = $row['total'];
         }
      }

      $arrayOfIncome = $con->query("SELECT distinct account_class from trial_balance where workspace_id = $wid and account_type like '%Income%'");
      $i = 0;
      $finalArrayOfIncome = []; 
      while($arrayOfIncomeRow = $arrayOfIncome->fetch_assoc()){
         $arrayOfIncomeResult = $con->query("SELECT max(financial_statement) financial_statement, sum(cy_final_bal) total FROM trial_balance where account_class = '".$arrayOfIncomeRow['account_class']."' GROUP BY financial_statment");
         while($row = $arrayOfIncomeResult->fetch_assoc()){
            $finalArrayOfIncome[$i][0] = $row['financial_statement'];
            $finalArrayOfIncome[$i++][1] = $row['total'];
         }
      }

      $maxIteration = count($finalArrayOfExpenses);
      
      $totalAmountForExpense = $totalAmountForIncome = 1;
      for($i=0;$i<$maxIteration;$i++) {
         if(isset($finalArrayOfIncome[$i])) {
            $name = $finalArrayOfIncome[$i][0];
            $amount = $finalArrayOfIncome[$i][1];
            $totalAmountForIncome += $amount; //amount field
         }
         else {
            $name = '';
            $amount = '';
         }
         $totalAmountForExpense += $finalArrayOfExpenses[$i][1];
         $htmlContent .= 
         '<tr>
            <td>'.$finalArrayOfExpenses[$i][0].'</td>
            <td>'.($finalArrayOfExpenses[$i][1]).'</td>';
         if($i == 0) {
            $htmlContent .= 
         '<td>By Gross Profit</td>
         <td>'.($grossTotal).'</td>';
         } else {
            $htmlContent .= 
            '<td>'.$name.'</td>
            <td>'.($amount).'</td>';
         }

         $htmlContent .= '</tr>';
      }

      $htmlContent .= 
            '<tr>
               <td>To Net profit</td>
               <td>'.($totalAmountForExpense).'</td>
               <td>&nbsp;</td>
               <td>&nbsp;</td>
            </tr>
            <tr>
               <td>TOTAL</td>
               <td>'.($totalAmountForExpense).'</td>
               <td>TOTAL</td>
               <td>'.($totalAmountForIncome).'</td>
            </tr>';
      $htmlContent .= '
            </tbody>
         </table>
      </div>';
   }
   header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
   header("Content-Disposition: attachment; filename=Financial Statement.xls");
   echo $htmlContent;
?>