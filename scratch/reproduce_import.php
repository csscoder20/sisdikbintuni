<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Filament\Imports\SiswaImporter;
use Filament\Actions\Imports\Models\Import;
use App\Models\User;
use App\Models\Sekolah;

// Mock context
$user = User::first(); 
if (!$user) die("No user found");
auth()->login($user);

$sekolah = Sekolah::first();
if (!$sekolah) die("No sekolah found");

// Mock Import model
$importMock = new Import();
$importMock->setRelation('user', $user);

// Column map based on the data provided
$columnMap = [
    'nama' => 0,
    'nisn' => 1,
    'nik' => 2,
    'tempat_lahir' => 3,
    'tanggal_lahir' => 4,
    'jenis_kelamin' => 5,
    'agama' => 6,
    'nokk' => 7,
    'nobpjs' => 8,
    'daerah_asal' => 9,
    'alamat' => 10,
    'provinsi' => 11,
    'kabupaten' => 12,
    'kecamatan' => 13,
    'desa' => 14,
    'nama_ayah' => 15,
    'nama_ibu' => 16,
    'nama_wali' => 17,
    'status' => 18,
    'disabilitas' => 19,
    'beasiswa' => 20,
    'kelas_rombel' => 21,
];

// User's provided data
$row = [
    'Santoso', '1234567890', '3201010154010001', 'Bandung', '20/08/2011', 'Laki-laki', 'Islam', 
    '3202310101010002', '0002334567890', 'Non Papua', 'Jl. Merdeka No. 10', 'Jawa Barat', 'Bandung', 
    'Coblong', 'Dago', 'Ahmad Santoso', 'Siti Aminah', 'Paman Budi', 'Aktif', 'Tidak', 'Tidak', 'Kelas XII IPS 1'
];

try {
    // Manually set tenant for Filament
    filament()->setTenant($sekolah);

    $importer = new SiswaImporter($importMock, $columnMap, []);
    
    // Simulate the import process
    $importer($row);
    
    $siswa = \App\Models\Siswa::where('nama', 'Santoso')->first();
    if ($siswa) {
        echo "Success! Siswa created: " . $siswa->nama . "\n";
        echo "Tanggal Lahir: " . $siswa->tanggal_lahir . "\n";
        echo "Sekolah ID: " . $siswa->sekolah_id . "\n";
        $siswa->delete(); // Clean up
    } else {
        echo "Failed to create Siswa record.\n";
    }
} catch (\Illuminate\Validation\ValidationException $e) {
    echo "Validation Error: " . json_encode($e->errors()) . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack: " . $e->getTraceAsString() . "\n";
}
