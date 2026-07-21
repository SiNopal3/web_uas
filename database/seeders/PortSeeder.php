<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PortSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ports = [
            // Asia Timur & Tenggara
            ['name' => 'Port of Shanghai', 'location' => '31.2304,121.4737', 'country' => 'China'],
            ['name' => 'Ningbo-Zhoushan Port', 'location' => '29.8683,121.5440', 'country' => 'China'],
            ['name' => 'Shenzhen Port', 'location' => '22.5431,114.0579', 'country' => 'China'],
            ['name' => 'Port of Hong Kong', 'location' => '22.3193,114.1694', 'country' => 'China'],
            ['name' => 'Port of Singapore', 'location' => '1.2902,103.8519', 'country' => 'Singapore'],
            ['name' => 'Jurong Port Terminal', 'location' => '1.3060,103.7170', 'country' => 'Singapore'],
            ['name' => 'Port Klang', 'location' => '3.0000,101.3833', 'country' => 'Malaysia'],
            ['name' => 'Tanjung Pelepas Port', 'location' => '1.3636,103.5469', 'country' => 'Malaysia'],
            ['name' => 'Penang Port', 'location' => '5.4141,100.3444', 'country' => 'Malaysia'],
            ['name' => 'Tanjung Priok Port', 'location' => '-6.1021,106.8833', 'country' => 'Indonesia'],
            ['name' => 'Tanjung Perak Port Surabaya', 'location' => '-7.1983,112.7319', 'country' => 'Indonesia'],
            ['name' => 'Belawan Port Medan', 'location' => '3.7842,98.6833', 'country' => 'Indonesia'],
            ['name' => 'Makassar New Port', 'location' => '-5.1186,119.4144', 'country' => 'Indonesia'],
            ['name' => 'Port of Tokyo', 'location' => '35.6190,139.7766', 'country' => 'Japan'],
            ['name' => 'Port of Yokohama', 'location' => '35.4437,139.6380', 'country' => 'Japan'],
            ['name' => 'Port of Kobe', 'location' => '34.6851,135.1963', 'country' => 'Japan'],
            ['name' => 'Port of Busan', 'location' => '35.1028,129.0403', 'country' => 'South Korea'],
            ['name' => 'Incheon Port', 'location' => '37.4563,126.6052', 'country' => 'South Korea'],
            ['name' => 'Cat Lai Port Ho Chi Minh', 'location' => '10.7626,106.7868', 'country' => 'Vietnam'],
            ['name' => 'Hai Phong Port', 'location' => '20.8648,106.6835', 'country' => 'Vietnam'],
            ['name' => 'Laem Chabang Port', 'location' => '13.0827,100.8842', 'country' => 'Thailand'],
            ['name' => 'Bangkok Port (Klong Toey)', 'location' => '13.7056,100.5694', 'country' => 'Thailand'],
            ['name' => 'Port of Manila', 'location' => '14.5878,120.9633', 'country' => 'Philippines'],
            ['name' => 'Port of Cebu', 'location' => '10.3090,123.9080', 'country' => 'Philippines'],

            // Asia Selatan & Timur Tengah
            ['name' => 'Nhava Sheva (Jawaharlal Nehru Port)', 'location' => '18.9496,72.9510', 'country' => 'India'],
            ['name' => 'Mundra Port', 'location' => '22.7486,69.7042', 'country' => 'India'],
            ['name' => 'Chennai Port', 'location' => '13.0900,80.2950', 'country' => 'India'],
            ['name' => 'Port of Jebel Ali', 'location' => '25.0113,55.0612', 'country' => 'United Arab Emirates'],
            ['name' => 'Abu Dhabi Khalifa Port', 'location' => '24.8465,54.6644', 'country' => 'United Arab Emirates'],
            ['name' => 'Jeddah Islamic Port', 'location' => '21.4858,39.1731', 'country' => 'Saudi Arabia'],
            ['name' => 'King Abdulaziz Port Dammam', 'location' => '26.5050,50.1888', 'country' => 'Saudi Arabia'],
            ['name' => 'Hamad Port Doha', 'location' => '25.0211,51.5975', 'country' => 'Qatar'],

            // Eropa
            ['name' => 'Port of Rotterdam', 'location' => '51.9244,4.4777', 'country' => 'Netherlands'],
            ['name' => 'Port of Amsterdam', 'location' => '52.4082,4.8328', 'country' => 'Netherlands'],
            ['name' => 'Port of Hamburg', 'location' => '53.5511,9.9937', 'country' => 'Germany'],
            ['name' => 'Bremerhaven Port', 'location' => '53.5511,8.5714', 'country' => 'Germany'],
            ['name' => 'Port of Antwerp-Bruges', 'location' => '51.2728,4.3644', 'country' => 'Belgium'],
            ['name' => 'Port of Felixstowe', 'location' => '51.9567,1.3086', 'country' => 'United Kingdom'],
            ['name' => 'Port of Southampton', 'location' => '50.8973,-1.4042', 'country' => 'United Kingdom'],
            ['name' => 'Port of London Gateway', 'location' => '51.5033,0.4566', 'country' => 'United Kingdom'],
            ['name' => 'Port of Le Havre', 'location' => '49.4842,0.1172', 'country' => 'France'],
            ['name' => 'Port of Marseille Fos', 'location' => '43.4071,4.9603', 'country' => 'France'],
            ['name' => 'Port of Valencia', 'location' => '39.4458,-0.3150', 'country' => 'Spain'],
            ['name' => 'Port of Algeciras', 'location' => '36.1288,-5.4411', 'country' => 'Spain'],
            ['name' => 'Port of Barcelona', 'location' => '41.3534,2.1686', 'country' => 'Spain'],
            ['name' => 'Port of Genoa', 'location' => '44.4056,8.9178', 'country' => 'Italy'],
            ['name' => 'Port of Gioia Tauro', 'location' => '38.4411,15.9031', 'country' => 'Italy'],
            ['name' => 'Port of Piraeus Athens', 'location' => '37.9425,23.6331', 'country' => 'Greece'],
            ['name' => 'Port of Gdansk', 'location' => '54.4022,18.6631', 'country' => 'Poland'],
            ['name' => 'Ambarli Port Istanbul', 'location' => '40.9650,28.6833', 'country' => 'Turkey'],
            ['name' => 'Mersin International Port', 'location' => '36.7933,34.6400', 'country' => 'Turkey'],

            // Amerika Utara & Selatan
            ['name' => 'Port of Los Angeles', 'location' => '33.7405,-118.2786', 'country' => 'United States'],
            ['name' => 'Port of Long Beach', 'location' => '33.7542,-118.2165', 'country' => 'United States'],
            ['name' => 'Port of New York and New Jersey', 'location' => '40.6698,-74.0451', 'country' => 'United States'],
            ['name' => 'Port of Savannah', 'location' => '32.1286,-81.1441', 'country' => 'United States'],
            ['name' => 'Port of Vancouver', 'location' => '49.2890,-123.1111', 'country' => 'Canada'],
            ['name' => 'Port of Montreal', 'location' => '45.5488,-73.5358', 'country' => 'Canada'],
            ['name' => 'Port of Manzanillo', 'location' => '19.0525,-104.2980', 'country' => 'Mexico'],
            ['name' => 'Port of Veracruz', 'location' => '19.2014,-96.1264', 'country' => 'Mexico'],
            ['name' => 'Port of Santos', 'location' => '-23.9619,-46.3086', 'country' => 'Brazil'],
            ['name' => 'Port of Paranagua', 'location' => '-25.5033,-48.5144', 'country' => 'Brazil'],
            ['name' => 'Port of Buenos Aires', 'location' => '-34.5889,-58.3667', 'country' => 'Argentina'],
            ['name' => 'Port of San Antonio', 'location' => '-33.5833,-71.6167', 'country' => 'Chile'],

            // Afrika & Oseania
            ['name' => 'Port Said (Suez Canal Terminal)', 'location' => '31.2565,32.3019', 'country' => 'Egypt'],
            ['name' => 'Port of Alexandria', 'location' => '31.1873,29.8710', 'country' => 'Egypt'],
            ['name' => 'Port of Tanger Med', 'location' => '35.8889,-5.5000', 'country' => 'Morocco'],
            ['name' => 'Port of Durban', 'location' => '-29.8683,31.0333', 'country' => 'South Africa'],
            ['name' => 'Port of Cape Town', 'location' => '-33.9042,18.4356', 'country' => 'South Africa'],
            ['name' => 'Port of Lagos (Apapa & Tin Can)', 'location' => '6.4422,3.3644', 'country' => 'Nigeria'],
            ['name' => 'Port of Mombasa', 'location' => '-4.0628,39.6644', 'country' => 'Kenya'],
            ['name' => 'Port of Sydney', 'location' => '-33.8688,151.2093', 'country' => 'Australia'],
            ['name' => 'Port of Melbourne', 'location' => '-37.8333,144.9167', 'country' => 'Australia'],
            ['name' => 'Port of Brisbane', 'location' => '-27.3833,153.1667', 'country' => 'Australia'],
            ['name' => 'Port of Auckland', 'location' => '-36.8433,174.7766', 'country' => 'New Zealand'],
            ['name' => 'Port of Tauranga', 'location' => '-37.6467,176.1833', 'country' => 'New Zealand'],
        ];

        foreach ($ports as $port) {
            DB::table('ports')->updateOrInsert(
                ['name' => $port['name']],
                ['location' => $port['location'], 'country' => $port['country'], 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
