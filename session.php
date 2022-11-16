<?php

session_start();

require_once '../class-user.php';
$session = new USER();

// If user session is not set you will redirected to 'index.php'
/* Use session.php for pages in the admin directory to secure the pages access
	if user not logged in he/she will not authorized to use those pages, base on user roles
	*/

if (!$session->is_loggedin()) {
	// If session no set redirects to index page
	$session->redirect('../index.php');
}
