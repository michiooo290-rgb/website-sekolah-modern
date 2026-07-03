<?php
require_once 'auth.php';
require_login();

$pdo   = db();
$edit  = null;
$mode  = 'list';

// ── Handle actions ─────────────────────────────────
inif_placeholder