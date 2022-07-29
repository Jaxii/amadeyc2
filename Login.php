<?php

   include( "Cfg/Config.php" );
   
   session_start();

   error_reporting( 0 );

   function cPhpV()
   {
      $main_vers = substr( phpversion(), 0, 1 );
      $sub_vers = substr( phpversion(), 2, 1 );
      $pmess = "Incorrect PHP version, use 5.6 or lower";

      if ( $main_vers . $sub_vers > 56 )
      {
         echo $pmess;
         exit;
      }
   }

   cPhpV();
   
   if ( isset( $_POST["login"]) && isset( $_POST["password"] ) )
   {
      $login = $_POST["login"];
      $password = $_POST["password"];

      if ( ( $login == $conf["login"] ) && ( md5( $password ) == $conf["password"] ) )
      {
         $_SESSION["Name"] = $conf["login"];
         @header( "Refresh: 0; url = Statistic.php" );
      }

      if ( ( $login == $conf["observer_login"] ) && ( md5( $password ) == $conf["observer_password"] ) )
      {
         $_SESSION["Name"] = $conf["observer_login"];
         @header( "Refresh: 0; url = Statistic.php" );
      }

   }

   if ( $_GET['logout'] == 1 ) 
   {
      @session_destroy();
      header( "Location: Login.php" );
      exit;
   }
    
   echo "<html>
            <head>
               <meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1251\">
               <link rel=\"stylesheet\" type=\"text/css\" href=\"Css\Main.css\">
               <title>Authorization</title>
            </head>

            <body>
                  <table border=\"0\" width=\"100%\" height=\"100%\">
                     <tr>
                        <td align=center>
                           <form action=\"Login.php\" method=\"post\"> 
                              <table width=\"515\" height=\"481\" background=\"Images\bg_1.png\">
                                 <tr>
                                    <td align=center>
                                       <table border=\"0\" height=\"120\" cellpadding=\"0\" cellspacing=\"0\">
                                          <tr>
                                             <td></td> 
                                             <td></td>
                                          </tr>
                                          <tr>
                                             <td></td>
                                             <td align=left><input type=\"text\" class=task name=\"login\"></td>
                                          </tr>
                                          <tr>  
                                             <td></td>                                     
                                             <td><input type=\"password\" class=task name=\"password\"></td>
                                          </tr>
                                          <tr>   
                                             <td></td>
                                             <td>
                                                <div align=\"center\">
                                                   <input type=\"submit\" class=\"button\" value=\"Unlock\">
                                                </div> 
                                             </td>
                                          </tr>
                                       </table>
                                    </td>
                                 </tr>
                              </table>

                           </form>
                        </td>
                     </tr>
                  </table>
            </body>
         </html>";   
?>


