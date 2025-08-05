<?php

$hash1 = '$2y$10$52N2nJ9b3o/bhozS/staJuACX6T4sKFtp8UPdLw.JqDF8E.Yb.cza';
$hash2 = '$2y$10$xaX5rwkaduck18HVspMvRu2Jbn0TtXvLmWSW.9v6xel3kPY4Uh0Dy';
$password = '123qwe';

if (password_verify($password, $hash1)) {
    echo "Hash 1 matches the password.\n";
} else {
    echo "Hash 1 does not match the password.\n";
}

if (password_verify($password, $hash2)) {
    echo "Hash 2 matches the password.\n";
} else {
    echo "Hash 2 does not match the password.\n";
}
