kCpanel
=======

This project is written in PHP/jQuery using bootstrap for designing for SA-MP managing.
I've started working at this project for a long time ago, then I lost interest in it and left it.
Last month, I've started to work on it again and re-wrote a lot of parts.

**Note: This project is not fully done. I am still going to work on improving it and adding new features.

Features
========

+ View the player list by clicking on the player count
+ Control your server by starting/stopping/GMX'ing/restarting it
+ View/Edit server.cfg
+ View/Edit samp.ban
+ View server_log.txt
+ RCON Console
+ Ability to Update/Downgrade SA-MP version!
+ You can add multiple users & servers and allowing whom can access each server

Installation
============

+ Copy the files in src/ to the desired directory on your web server.
+ Copy 'libs' directory to the root directory of that project [recommended]
+ You can put it somewhere else, but you do need to specify the location in *src/libcheck.php*
+ Make sure you edit **config.php** (Almost everything is explained in that file)
+ Make sure you import the MySQL structure if you are using MySQL (file: **structure.sql**)

Requirements
============

+ PHP
+ [Bootstrap] (http://getboostrap.com)
+ [phpseclib] (http://phpseclib.sourceforge.net/)
+ [SampQueryAPI & RconQueryAPI by Westie] (http://forum.sa-mp.com/showthread.php?t=104299) **(Notice that I have modified SampQueryAPI.php to add the support of 0.3.7 'language')**
+ [jquery.terminal] (https://github.com/Bloutiouf/jquery.terminal) **(for RCON console)**

Bugs
====

If any bugs are found, please report them on the issues page and I'll fix it asap!