<?php
   include_once 'HtmlToDoc.class.php';  
   include 'dbconnection.php';
   include 'moneyFormatterFPDF.php';
   if(!isset($_SESSION)){
       session_start();
    }
   $wid = $_SESSION['workspace_id'];
   $clientId = $_SESSION['client_id'];
   $htmlContent = '';
   $lineCount = 0;
   // Initialize class 
   $htd = new HTML_TO_DOC();

   function headerWord($constitutionType, $financialStatementType, $con){
      $clientId = $_SESSION['client_id'];
      $wid = $_SESSION['workspace_id'];
      if($constitutionType == 1){
         if($financialStatementType == 'BS'){
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
         }
         else{
            $htmlContent = '
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
         }
      }
      else{
         if($financialStatementType == 'BS'){
            $htmlContent = '
            <div>
               <div style="text-align:center">
                  <h3>'.$con->query("SELECT concat(name, ' ', const) details FROM `client` inner join industry on client.industry_id = industry.id inner join constitution on client.const_id = constitution.id where client.id = $clientId")->fetch_assoc()['details'].'</h3>
                  <h5><i>'.$con->query("SELECT concat(address,', ', city, ', ', state, '-', pincode,', ',country) address FROM `client` inner join industry on client.industry_id = industry.id inner join constitution on client.const_id = constitution.id where client.id = $clientId")->fetch_assoc()['address'].'</i></h3>
                  <h5><i>'.$con->query("SELECT concat('Balance Sheet as on ', dateto) workspaceDate from workspace where client_id = $clientId")->fetch_assoc()['workspaceDate'].'</i></h3>
               </div>';
         }
         else{
            $htmlContent = '
            <div>
               <div style="text-align:center">
                  <h3>'.$con->query("SELECT concat(name, ' ', const) details FROM `client` inner join industry on client.industry_id = industry.id inner join constitution on client.const_id = constitution.id where client.id = $clientId")->fetch_assoc()['details'].'</h3>
                  <h5><i>'.$con->query("SELECT concat(address,', ', city, ', ', state, '-', pincode,', ',country) address FROM `client` inner join industry on client.industry_id = industry.id inner join constitution on client.const_id = constitution.id where client.id = $clientId")->fetch_assoc()['address'].'</i></h3>
                  <h5><i>'.$con->query("SELECT concat('Profit and Loss as on ', dateto) workspaceDate from workspace where client_id = $clientId")->fetch_assoc()['workspaceDate'].'</i></h3>
               </div>';
         }
         $htmlContent .= '<br><table class="table">
         <thead>
            <tr>
               <th>Particulars</th>
               <th>'.$con->query("SELECT dateto from workspace where id = $wid")->fetch_assoc()['dateto'].'</th>
               <th>Particulars</th>
               <th>'.$con->query("SELECT dateto from workspace where id = $wid")->fetch_assoc()['dateto'].'</th>
            </tr>
         </thead>
         <tbody>';

      }
      return $htmlContent;
   }

   function footerWord(){
      $htmlContent = '</tbody></table>
         <div>
            <div class="d-flex justify-content-between align-items-center">
               Place:______________________&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;______________________<br><br>
               Date: '.date('d-m-Y').'&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&nbsp;&nbsp;______________________<br><br>
               UDIN:______________________&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;______________________<br><br>
            </div>
            <div>
               <br><br><br><br><br><br><br><br><br><br>
            </div>
            <div>
                  ______________________&emsp;&emsp;______________________&emsp;&emsp;______________________
            </div>
         </div>
      </div>';
      return $htmlContent;
   }

   $const = $con->query("SELECT const_id FROM client where id = $clientId")->fetch_assoc()['const_id'];
   if($const == 2){
      // Balance Sheet starts from here
      $htmlContent .= headerWord(1, 'BS', $con);
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
         $lineCount++;
         if($lineCount == 15){
            $htmlContent .= footerWord();
            $htmlContent .= '<br style="page-break-before: always">';
            $htmlContent .= headerWord(1,'BS', $con);
            $lineCount = 0;
         }
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
            $lineCount++;
            if($lineCount == 15){
               $htmlContent .= footerWord();
               $htmlContent .= '<br style="page-break-before: always">';
               $htmlContent .= headerWord(1,'BS', $con);
               $lineCount = 0;
            }
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
               $lineCount++;
               if($lineCount == 15){
                  $htmlContent .= footerWord();
                  $htmlContent .= '<br style="page-break-before: always">';
                  $htmlContent .= headerWord(1,'BS', $con);
                  $lineCount = 0;
               }
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
         $lineCount++;
         if($lineCount == 15){
            $htmlContent .= footerWord();
            $htmlContent .= '<br style="page-break-before: always">';
            $htmlContent .= headerWord(1,'BS', $con);
            $lineCount = 0;
         }
      }
      $htmlContent .= footerWord();
      $htmlContent .= '<br style="page-break-before: always">';
   
      // Profit and Loss starts from here
      $htmlContent .= headerWord(1,'PL', $con);
      $lineCount = 0;
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
         $lineCount++;
         if($lineCount == 15){
            $htmlContent .= footerWord();
            $htmlContent .= '<br style="page-break-before: always">';
            $htmlContent .= headerWord(1,'PL', $con);
            $lineCount = 0;
         }
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
            $lineCount++;
            if($lineCount == 15){
               $htmlContent .= footerWord();
               $htmlContent .= '<br style="page-break-before: always">';
               $htmlContent .= headerWord(1,'PL', $con);
               $lineCount = 0;
            }
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
               $lineCount++;
               if($lineCount == 15){
                  $htmlContent .= footerWord();
                  $htmlContent .= '<br style="page-break-before: always">';
                  $htmlContent .= headerWord(1,'PL', $con);
                  $lineCount = 0;
               }
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
         $lineCount++;
         if($lineCount == 15){
            $htmlContent .= footerWord();
            $htmlContent .= headerWord(1,'PL', $con);
            $lineCount = 0;
         }
      }
      $htmlContent .= footerWord();
   }
   else{

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
               <td>'.numberToCurrency($amount).'</td>
               <td>'.$finalArrayOfAssets[$i][0].'</td>
               <td>'.numberToCurrency($finalArrayOfAssets[$i][1]).'</td>
            </tr>';
            $lineCount++;
            if($lineCount == 15){
               $htmlContent .= footerWord();
               $htmlContent .= '<br style="page-break-before: always">';
               $htmlContent .= headerWord(2,'BS', $con);
               $lineCount = 0;
            }
      }

      $htmlContent .= 
            '<tr>
               <td>TOTAL</td>
               <td>'.numberToCurrency($totalLiabilities).'</td>
               <td>TOTAL</td>
               <td>'.numberToCurrency($totalAssets).'</td>
            </tr>';
            $lineCount++;
            if($lineCount == 15){
               $htmlContent .= footerWord();
               $htmlContent .= '<br style="page-break-before: always">';
               $htmlContent .= headerWord(2,'BS', $con);
               $lineCount = 0;
            }
      
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
               <td>'.numberToCurrency($amount).'</td>
            </tr>';
            $lineCount++;
            if($lineCount == 15){
               $htmlContent .= footerWord();
               $htmlContent .= '<br style="page-break-before: always">';
               $htmlContent .= headerWord(2,'PL', $con);
               $lineCount = 0;
            }
      }
      //gross profit
      $grossTotal = $totalAmountForSales - $totalAmountForPurchase;

      $htmlContent .= 
            '<tr>
               <td>Gross Profit</td>
               <td>'.numberToCurrency($grossTotal).'</td>
               <td>&nbsp;</td>
               <td>&nbsp;</td>
            </tr>
            <tr>
               <td>TOTAL</td>
               <td>'.numberToCurrency($totalAmountForPurchase).'</td>
               <td>TOTAL</td>
               <td>'.numberToCurrency($totalAmountForSales).'</td>
            </tr>';
            $lineCount++;
            if($lineCount == 15){
               $htmlContent .= footerWord();
               $htmlContent .= '<br style="page-break-before: always">';
               $htmlContent .= headerWord(2,'PL', $con);
               $lineCount = 0;
            }

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
            <td>'.numberToCurrency($finalArrayOfExpenses[$i][1]).'</td>';
         if($i == 0) {
            $htmlContent .= 
         '<td>By Gross Profit</td>
         <td>'.numberToCurrency($grossTotal).'</td>';
         } else {
            $htmlContent .= 
            '<td>'.$name.'</td>
            <td>'.numberToCurrency($amount).'</td>';
         }

         $htmlContent .= '</tr>';
         $lineCount++;
         if($lineCount == 15){
            $htmlContent .= footerWord();
            $htmlContent .= '<br style="page-break-before: always">';
            $htmlContent .= headerWord(2,'PL', $con);
            $lineCount = 0;
         }
      }

      $htmlContent .= 
            '<tr>
               <td>To Net profit</td>
               <td>'.numberToCurrency($totalAmountForExpense).'</td>
               <td>&nbsp;</td>
               <td>&nbsp;</td>
            </tr>
            <tr>
               <td>TOTAL</td>
               <td>'.numberToCurrency($totalAmountForExpense).'</td>
               <td>TOTAL</td>
               <td>'.numberToCurrency($totalAmountForIncome).'</td>
            </tr>';
      $htmlContent .= footerWord();
   }
   $htd->createDoc($htmlContent, "Financial Statement.doc", 1);
?>