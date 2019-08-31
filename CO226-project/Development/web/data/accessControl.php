<?php

if (FOLDER_NAME == "student") {
    if (!($_SESSION['role'] == 0 || $_SESSION['role'] == 4)) {
        include_once '../404.shtml';
        exit;
    }
} else if (FOLDER_NAME == "course") {
    if (!($_SESSION['role'] == 0 || $_SESSION['role'] == 4)) {
        include_once '../404.shtml';
        exit;
    }
} else if (FOLDER_NAME == "classes") {
    if (!($_SESSION['role'] == 0 || $_SESSION['role'] == 4)) {
        include_once '../404.shtml';
        exit;
    }
} else if (FOLDER_NAME == "attendance") {
    if (!($_SESSION['role'] == 0 || $_SESSION['role'] == 2 || $_SESSION['role'] == 3 || $_SESSION['role'] == 4)) {
        include_once '../404.shtml';
        exit;
    }
} else if (FOLDER_NAME == "classlist") {
    if (!($_SESSION['role'] == 0 || $_SESSION['role'] == 2 || $_SESSION['role'] == 3 || $_SESSION['role'] == 4)) {
        include_once '../404.shtml';
        exit;
    }
} else if (FOLDER_NAME == "student") {
    // Anyone can access
} else if (FOLDER_NAME == "home") {
    // allow
} else {
    include_once '../404.shtml';
    exit;
}