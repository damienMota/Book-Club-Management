<?php
function buildSimpleTable($rs,$tableId,$columnExclusions,$idColumn,$includeFooter,$headers=null)
{
    $nonNumeric = array("card","card_4","reference_number","payment_request_id","tax_id","card_last_4","card_exp","decline_code","number_of_declines","city","xid","transaction_count","trans_number","ticket_number","pce_id","account",
    "film_title","advance_account_id","bank_ref","zip","telephone","vendor_id","cust_ref","phone","decline_code","approval_code", "check_number", "form_title","batch_number","number_of_payments",
    "form_description","spend_categ","mcc_code","price_per_unit","quantity","cell_number","contracts","corporation_id","corp_id","row_id","request_id","approval_code","remittance_number","AUTH/APPROVAL #","vendor_id","tin","created_by","contract_name","corp_code","application_number");
    $ret = '<table class="stripe cell-border compact" cellspacing="0" width="100%" id="'.$tableId.'">';
    $ret .= '<thead>';
    if (isset($headers))
    {
   	 $ret .= $headers;
    }
    else
    {
   	 $ret .= '<tr>';
   	 for ($i=0;$i<pg_num_fields($rs);$i++)
   	 {
   		 if (is_null($columnExclusions) || !in_array(pg_field_name($rs, $i),$columnExclusions))
   		 {
   			 $ret .= '<th class="dt-'.str_replace("/","",pg_field_name($rs, $i)).'">';
   			 $ret .= strtoupper(str_replace("_"," ",pg_field_name($rs, $i)));
   			 $ret .= '</th>';
   		 }
   	 }
   	 $ret .= '</tr>';
    }
    $ret .= '</thead>';
    if ($includeFooter)
    {
   	 $ret .= '<tfoot><tr>';
   	 for ($i=0;$i<pg_num_fields($rs);$i++)
   	 {
   		 if (is_null($columnExclusions) || !in_array(pg_field_name($rs, $i),$columnExclusions))
   		 {
   			 $ret .= '<th></th>';
   		 }
   	 }
   	 $ret .= '</tfoot>';
    }
    $ret .= '<tbody>';
    $rowCount = 0;
    while ($row = pg_fetch_assoc($rs))
    {
   	 if ($rowCount % 2 == 0)
   	 {
   		 $evenOdd = "even";
   	 }
   	 else
   	 {
   		 $evenOdd = "odd";
   	 }
   	 $ret .= '<tr '.((isset($idColumn))?'value="'.$row[$idColumn].'"':'').' class="dt-row '.$evenOdd.'">';
   	 for ($i=0;$i<count($row);$i++)
   	 {
   		 if (isset($row["background_color"]))
   		 {
   			 $backgroundColor = $row["background_color"];
   		 }
   		 else
   		 {
   			 unset($backgroundColor);
   		 }
   		 if (is_null($columnExclusions) || !in_array(pg_field_name($rs, $i),$columnExclusions))
   		 {
   			 $ret .= '<td '.((isset($idColumn) && !is_null($idColumn))?'id="'.pg_field_name($rs, $i).'-'.$row[$idColumn].'"':'').' class="dt-'.pg_field_name($rs, $i);
   			 if (pg_field_name($rs, $i) == "details_control")
   			 {
   				 $ret .= '-'.$row[pg_field_name($rs, $i)];
   			 }
   			 $ret .= '"';
   			 if (isset($backgroundColor))
   			 {
   				 $ret .= ' style="background-color:'.$row["background_color"].' !important;"';
   			 }
   			 $ret .= '>';
   			 $ret .= ((is_numeric($row[pg_field_name($rs, $i)]) && !in_array(pg_field_name($rs, $i),$nonNumeric))?number_format($row[pg_field_name($rs, $i)],2):$row[pg_field_name($rs, $i)]);
   			 $ret .= '</td>';
   		 }
   	 }
   	 $ret .= '</tr>'."\n";
   	 $rowCount++;
    }
    $ret .= '</tbody></table>';
    return $ret;
}
>
