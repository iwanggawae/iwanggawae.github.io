<?php
echo "Memulai instalasi...\n";

// Periksa apakah Composer terinstal
if (!file_exists('composer.phar')) {
    echo "Mengunduh Composer...\n";
    file_put_contents('composer-setup.php', file_get_contents('https://getcomposer.org/installer'));
    shell_exec('php composer-setup.php');
    unlink('composer-setup.php');
}

// Jalankan perintah Composer untuk menginstal dependensi
echo "Menginstal dependensi menggunakan Composer...\n";
shell_exec('php composer.phar install');

// Hapus file composer.phar jika tidak diperlukan lagi
// unlink('composer.phar');

echo "Instalasi selesai.\n";
?>
