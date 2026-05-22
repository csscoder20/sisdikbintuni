<?php

namespace Database\Seeders\Concerns;

use Carbon\Carbon;

trait RealisticDummyData
{
    protected array $maleFirstNames = [
        'Andika', 'Bima', 'Cahyo', 'Dimas', 'Emanuel', 'Farhan', 'Gilang', 'Hendra',
        'Iqbal', 'Jefri', 'Kevin', 'Lukas', 'Mario', 'Nataniel', 'Oktavianus', 'Petrus',
        'Rafael', 'Samuel', 'Taufik', 'Viktor', 'Wahyu', 'Yohanes', 'Zulkifli', 'Arif',
        'Benediktus', 'Christian', 'Dionisius', 'Eko', 'Fajar', 'Haris', 'Irfan',
        'Januar', 'Karel', 'Leonardo', 'Mikael', 'Niko', 'Oscar', 'Paulus', 'Rizky',
        'Sandro', 'Teguh', 'Valentino', 'Yusuf', 'Adrian', 'Brian', 'Darius', 'Frans',
        'Gregorius', 'Ilham', 'Jordi', 'Krisna', 'Moses', 'Novan', 'Reynold', 'Satria',
    ];

    protected array $femaleFirstNames = [
        'Aisyah', 'Bella', 'Citra', 'Dewi', 'Elisabeth', 'Fitri', 'Grace', 'Hana',
        'Indah', 'Jessica', 'Kartika', 'Lestari', 'Maria', 'Nabila', 'Olivia', 'Putri',
        'Rani', 'Sinta', 'Tiara', 'Vania', 'Wulan', 'Yuliana', 'Zahra', 'Anisa',
        'Bertha', 'Claudia', 'Diana', 'Ester', 'Febrianti', 'Helena', 'Intan',
        'Juliana', 'Kristina', 'Laura', 'Maya', 'Natalia', 'Oktaviana', 'Priskila',
        'Ruth', 'Selvi', 'Tania', 'Veronika', 'Yohana', 'Adelina', 'Brigitta',
        'Delima', 'Fransiska', 'Gabriela', 'Irene', 'Monika', 'Novi', 'Regina',
    ];

    protected array $middleNames = [
        'Adi', 'Bayu', 'Cipta', 'Dwi', 'Eka', 'Firmansyah', 'Gunawan', 'Halim',
        'Imanuel', 'Jaya', 'Kusuma', 'Laksana', 'Mahendra', 'Nugraha', 'Pratama',
        'Saputra', 'Setiawan', 'Wijaya', 'Pamungkas', 'Permana', 'Rahardian',
        'Anggraini', 'Puspita', 'Maharani', 'Safitri', 'Wulandari', 'Kurniawan',
        'Suryani', 'Handayani', 'Maulana', 'Kusnadi', 'Salsabila',
    ];

    protected array $papuanFamilyNames = [
        'Kambuaya', 'Mandacan', 'Manibuy', 'Hegemur', 'Bauw', 'Kareth', 'Rumbiak',
        'Imbiri', 'Sawaki', 'Hindom', 'Sagrim', 'Fatem', 'Dimara', 'Waromi',
        'Mayor', 'Maniani', 'Korwa', 'Jitmau', 'Renwarin', 'Wanggai', 'Solossa',
        'Rumainum', 'Krey', 'Ullo', 'Kbarek', 'Yarangga', 'Kosepa', 'Mofu',
        'Inai', 'Wayuri', 'Tuturop', 'Mote', 'Iba', 'Wambrauw',
    ];

    protected array $indonesianFamilyNames = [
        'Prasetyo', 'Santoso', 'Wijaya', 'Saputra', 'Nugroho', 'Maulana', 'Hidayat',
        'Kurniawan', 'Setiawan', 'Ramadhan', 'Permadi', 'Lestari', 'Wulandari',
        'Rahmawati', 'Sari', 'Utami', 'Pertiwi', 'Hasibuan', 'Sitorus', 'Simanjuntak',
        'Tampubolon', 'Siregar', 'Daeng', 'Latuconsina', 'Patty', 'Tuhuteru',
        'Latumahina', 'Pattiasina', 'Tanasale', 'Rumengan', 'Mokoginta', 'Tumbelaka',
        'Lasut', 'Tandi', 'Palullungan', 'Syam', 'Mansyur',
    ];

    protected array $streets = [
        'Jl. Pendidikan', 'Jl. Merdeka', 'Jl. Yos Sudarso', 'Jl. Ki Hajar Dewantara',
        'Jl. Raya Bintuni', 'Jl. Kampung Lama', 'Jl. Pasir Putih', 'Jl. Pelajar',
        'Jl. Trikora', 'Jl. Kasuari', 'Jl. Cenderawasih', 'Jl. Manimeri',
    ];

    protected array $birthPlaces = [
        'Bintuni', 'Manimeri', 'Merdey', 'Babo', 'Meyado', 'Tembuni', 'Tofoi',
        'Kaitaro', 'Aranday', 'Saengga', 'Kuri', 'Sorong', 'Manokwari', 'Fakfak',
        'Jayapura', 'Makassar', 'Ambon', 'Ternate', 'Serui', 'Biak',
    ];

    protected function personName(string $gender, int $seed, bool $preferPapua = false): string
    {
        $firstNames = $gender === 'P' ? $this->femaleFirstNames : $this->maleFirstNames;
        $familyNames = $preferPapua || $seed % 10 < 6
            ? $this->papuanFamilyNames
            : $this->indonesianFamilyNames;

        $parts = [
            $this->pick($firstNames, $seed),
            $this->pick($this->middleNames, $seed * 3 + 5),
            $this->pick($familyNames, $seed * 7 + 11),
        ];

        if ($seed % 4 === 0) {
            unset($parts[1]);
        }

        return implode(' ', $parts);
    }

    protected function pick(array $items, int $seed): mixed
    {
        return $items[abs($seed) % count($items)];
    }

    protected function weightedPick(array $weightedItems, int $seed): string
    {
        $total = array_sum($weightedItems);
        $cursor = abs($seed) % $total;

        foreach ($weightedItems as $item => $weight) {
            if ($cursor < $weight) {
                return $item;
            }

            $cursor -= $weight;
        }

        return array_key_first($weightedItems);
    }

    protected function studentBirthDate(int $tingkat, int $seed): Carbon
    {
        $age = match ((int) $tingkat) {
            10 => 15 + ($seed % 2),
            11 => 16 + ($seed % 2),
            12 => 17 + ($seed % 2),
            default => 15 + ($seed % 4),
        };

        return Carbon::now()->subYears($age)->subDays(($seed * 17) % 330);
    }

    protected function adultBirthDate(int $seed, int $minAge = 24, int $maxAge = 60): Carbon
    {
        $age = $minAge + (abs($seed) % max(1, $maxAge - $minAge + 1));

        return Carbon::now()->subYears($age)->subDays(($seed * 23) % 330);
    }

    protected function nik(Carbon $birthDate, string $gender, int $seed): string
    {
        $districtCode = str_pad((string) (1 + ($seed % 24)), 2, '0', STR_PAD_LEFT);
        $day = (int) $birthDate->format('d') + ($gender === 'P' ? 40 : 0);

        return '9206'
            . $districtCode
            . str_pad((string) $day, 2, '0', STR_PAD_LEFT)
            . $birthDate->format('my')
            . str_pad((string) (1000 + ($seed % 8999)), 4, '0', STR_PAD_LEFT);
    }

    protected function nisn(Carbon $birthDate, int $seed): string
    {
        return str_pad((string) ($birthDate->year % 1000), 3, '0', STR_PAD_LEFT)
            . str_pad((string) (1000000 + ($seed % 8999999)), 7, '0', STR_PAD_LEFT);
    }

    protected function nip(Carbon $birthDate, string $gender, int $seed): string
    {
        $tmt = Carbon::now()->subYears(1 + ($seed % 28))->subMonths($seed % 12);

        return $birthDate->format('Ymd')
            . $tmt->format('Ym')
            . ($gender === 'P' ? '2' : '1')
            . str_pad((string) (1 + ($seed % 997)), 3, '0', STR_PAD_LEFT);
    }

    protected function phone(int $seed): string
    {
        return '08' . str_pad((string) (1100000000 + ($seed * 7919) % 8800000000), 10, '0', STR_PAD_LEFT);
    }

    protected function address(object $sekolah, int $seed): string
    {
        return $this->pick($this->streets, $seed)
            . ' RT ' . str_pad((string) (1 + ($seed % 9)), 2, '0', STR_PAD_LEFT)
            . '/RW ' . str_pad((string) (1 + (($seed * 3) % 7)), 2, '0', STR_PAD_LEFT)
            . ', ' . ($sekolah->desa ?: $sekolah->kecamatan);
    }
}
