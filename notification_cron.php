#!/usr/bin/php
<?php

session_start();
require_once("classes/class.notifications.php");

$login = new NOTIFICATIONS();

$login->ex();
