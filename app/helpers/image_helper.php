<?php

function get_product_image($nama_produk, $kategori) {
    $nama = strtolower($nama_produk);
    
    // Check specific keywords in the product name
    if (strpos($nama, 'tahu lada') !== false || strpos($nama, 'tahu garam') !== false) {
        return 'tahu_lada_garam.png';
    } elseif (strpos($nama, 'tahu') !== false) {
        return 'tahu_goreng.png';
    } elseif (strpos($nama, 'tempe') !== false) {
        return 'tempe_goreng.png';
    } elseif (strpos($nama, 'pisang') !== false) {
        return 'pisang_coklat.png';
    } elseif (strpos($nama, 'kentang') !== false) {
        return 'kentang_goreng.png';
    } elseif (strpos($nama, 'gorengan') !== false) {
        return 'gorengan.png';
    } elseif (strpos($nama, 'ulen') !== false) {
        return 'ulen.png';
    } elseif (strpos($nama, 'kerupuk') !== false) {
        return 'kerupuk.png';
    } elseif (strpos($nama, 'mie') !== false || strpos($nama, 'indomie') !== false) {
        return 'mie.png';
    } elseif (strpos($nama, 'nasi goreng') !== false) {
        return 'nasi_goreng.png';
    } elseif (strpos($nama, 'nasi ayam') !== false) {
        return 'nasi_ayam.png';
    } elseif (strpos($nama, 'nasi telur') !== false) {
        return 'nasi_telur.png';
    } elseif (strpos($nama, 'nasi sayur') !== false) {
        return 'nasi_sayur.png';
    } elseif (strpos($nama, 'nasi') !== false) {
        return 'rice.png';
    } elseif (strpos($nama, 'americano') !== false) {
        return 'americano.png';
    } elseif (strpos($nama, 'kopi susu') !== false) {
        return 'kopi_susu.png';
    } elseif (strpos($nama, 'tubruk') !== false) {
        return 'kopi_tubruk.png';
    } elseif (strpos($nama, 'coklat') !== false) {
        return 'coklat.png';
    } elseif (strpos($nama, 'taro') !== false) {
        return 'taro.png';
    } elseif (strpos($nama, 'teh') !== false) {
        return 'teh.png';
    } elseif (strpos($nama, 'yakult') !== false) {
        return 'yakult.png';
    } elseif (strpos($nama, 'mineral') !== false || strpos($nama, 'air') !== false) {
        return 'air_mineral.png';
    } elseif (strpos($nama, 'ekstrajoss') !== false) {
        return 'ekstrajoss.png';
    } elseif (strpos($nama, 'seger mang') !== false || strpos($nama, 'asman') !== false) {
        return 'fresh_drink.png';
    } elseif (strpos($nama, 'asin sepat') !== false) {
        return 'asin_sepat.png';
    } elseif (strpos($nama, 'pindang') !== false) {
        return 'pindang.png';
    } elseif (strpos($nama, 'telur') !== false) {
        return 'telur.png';
    } elseif (strpos($nama, 'kornet') !== false) {
        return 'kornet.png';
    } elseif (strpos($nama, 'cemilan gantung') !== false) {
        return 'cemilan_gantung.png';
    }
    
    // Fallback based on category
    $gambar_map = [
        'Kopi' => 'coffee.png',
        'Susu' => 'milk.png',
        'Minuman Lain' => 'tea.png',
        'Nasi' => 'rice.png',
        'Cemilan' => 'snack.png',
        'Tambahan' => 'snack.png',
        'Mie' => 'noodles.png'
    ];
    return isset($gambar_map[$kategori]) ? $gambar_map[$kategori] : 'default.png';
}
