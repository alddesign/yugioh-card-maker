<?php
session_start();

error_reporting(E_ERROR);
ini_set('display_errors', 'off');

// Include configuration, core functions, and controller logic
include __DIR__.'/config.php';
include __DIR__.'/core.php';
include __DIR__.'/controller.php';

// Handle the incoming request
routeRequest();