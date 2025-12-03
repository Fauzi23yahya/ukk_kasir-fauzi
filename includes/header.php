<?php
// Cek apakah sudah include config
if (!isset($conn)) {
    require_once __DIR__ . '/../config/koneksi.php';
}

// Cek login
if (!isset($_SESSION['user_id'])) {
    // Deteksi path untuk redirect
    $current_path = $_SERVER['PHP_SELF'];
    if (strpos($current_path, '/pages/') !== false) {
        header('Location: ../../login.php');
    } else {
        header('Location: login.php');
    }
    exit();
}

$user_name = $_SESSION['user_name'];
$user_role = $_SESSION['user_role'];

// Deteksi base path berdasarkan lokasi file
$current_file = $_SERVER['PHP_SELF'];
if (strpos($current_file, '/pages/') !== false) {
    // File ada di pages/xxx/
    $base_path = '../..';
} else {
    // File di root
    $base_path = '.';
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Aplikasi Kasir UKK">
    <meta name="author" content="Fauzi">

    <title><?= isset($page_title) ? $page_title : 'Kasir UKK' ?> - Aplikasi Kasir</title>

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- SB Admin 2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/4.1.3/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<body id="page-top">
    <div id="wrapper">