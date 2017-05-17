<?php

session_start();

include 'nhinclude.php';

unset($_SESSION['username']);
unset($_SESSION['passwd']);
unset($_SESSION['loggedin']);
unset($_SESSION['userid']);
unset($_SESSION['lastlogin']);

unset($_SESSION['logged_in']);
unset($_SESSION['password']);

simple_redirect();
