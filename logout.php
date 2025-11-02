<?php
session_start();

// حذف كل بيانات الجلسة
session_unset();
session_destroy();

// إعادة التوجيه للصفحة الرئيسية
header("Location: index.php");
exit();
