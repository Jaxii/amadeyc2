<?php

    include( "Cfg/Lang.php" );

    echo 
    "<!DOCTYPE html>
        <html lang=\"en\" dir=\"ltr\">
      <head>
        <meta charset=\"UTF-8\">
        <title>CC [" . $_SERVER['SERVER_NAME'] . "]</title>
        <link rel=\"stylesheet\" href=\"Css\Style.css\">
        <link rel=\"shortcut icon\" href=\"Images\Ico.ico\" type=\"image/x-icon\">
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
      </head>
   
    <body>
      <div class=\"sidebar\">
        <div class=\"logo-details\">
          <i class='bx bxl-c-plus-plus icon'></i>
            <div class=\"logo_name\"></div>
            <i class='bx bx-menu' id=\"btn\" ><img src=\"Images\ic_0.png\"></i>
        </div>
        <ul class=\"nav-list\">
            <li>        
              <a href=\"Statistic.php\">
                <i><img src=\"Images\ic_1.png\"></i>        
                <span class=\"links_name\">" . $lang["__01"] ."</span>
              </a>
              <span class=\"tooltip\">" . $lang["__01"] . "</span>
            </li>
            <li>
              <a href=\"Show_Units.php\">
                <i><img src=\"Images\ic_2.png\"></i>
                <span class=\"links_name\">" . $lang["__02"] . "</span>
              </a>
              <span class=\"tooltip\">" . $lang["__02"] . "</span>
            </li>
            <li>
              <a href=\"Show_Units.php?show=all\">
                <i><img src=\"Images\ic_3.png\"></i>
                <span class=\"links_name\">" . $lang["__03"] . "</span>
              </a>
              <span class=\"tooltip\">" . $lang["__03"] . "</span>
            </li>
            <li>
              <a href=\"Show_Tasks.php\">
                <i><img src=\"Images\ic_4.png\"></i>
                <span class=\"links_name\">" . $lang["__04"] . "</span>
              </a>
              <span class=\"tooltip\">" . $lang["__04"] . "</span>
            </li>
            <li>
            <a href=\"Make_Task.php\">
              <i><img src=\"Images\ic_4.1.png\"></i>
              <span class=\"links_name\">" . $lang["__05"] . "</span>
            </a>
            <span class=\"tooltip\">" . $lang["__05"] . "</span>
            </li>
            <li>
              <a href=\"Show_Cred.php\">
                <i><img src=\"Images\ic_5.png\"></i>
                <span class=\"links_name\">" . $lang["__06"] . "</span>
              </a>
              <span class=\"tooltip\">" . $lang["__06"] . "</span>
            </li>
            <li>
              <a href=\"Settings.php\">
                <i><img src=\"Images\ic_6.png\"></i>
                <span class=\"links_name\">" . $lang["__07"] . "</span>
              </a>
              <span class=\"tooltip\">" . $lang["__07"] . "</span>
            </li>
            <li>
              <a href=\"Login.php?logout=1\">
                <i><img src=\"Images\ic_7.png\"></i>
                <span class=\"links_name\">" . $lang["__08"] . " [" . $_SESSION['Name'] . "]" . "</span>
              </a>
              <span class=\"tooltip\">" . $lang["__08"] . " [" . $_SESSION['Name'] . "]" ."</span>
            </li>
            <li class=\"profile\">
            </li>
        </ul>
      </div>


      <section class=\"home-section\">
      <div>"   

?>