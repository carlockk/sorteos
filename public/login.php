<?php
session_start();
require_once '../src/config.php';

// Generate state to validate callback
$state = bin2hex(random_bytes(8));
$_SESSION['fb_state'] = $state;
$loginUrl = 'https://www.facebook.com/v19.0/dialog/oauth?client_id='.
    FB_APP_ID.'&redirect_uri='.urlencode(FB_REDIRECT_URI).
    '&scope=pages_show_list,instagram_basic,instagram_manage_comments,pages_read_engagement&state='.
    $state;
header('Location: '.$loginUrl);
exit;
?>
