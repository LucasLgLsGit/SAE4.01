<?php

if (session_status() === PHP_SESSION_NONE) 
{
    ini_set('session.cookie_samesite', 'Lax');
    session_start();
}
if(!isset($_SESSION['panier']))
{
	$_SESSION['panier'] = [];
}