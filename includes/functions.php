<?php
// includes/functions.php

function isLoggedIn() {
    // This ensures a session exists to check
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Returns true if the user is logged in
    return isset($_SESSION['user_id']) || isset($_SESSION['student_id']);
}
?>