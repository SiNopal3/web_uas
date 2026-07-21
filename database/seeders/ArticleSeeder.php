<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articles = [
            [
                'title' => 'Analisis Ketahanan Koridor Maritim Selat Malaka Menghadapi Anomali Cuaca Ekstrem',
                'author' => 'Tim Analisis RiskIntel',
                'url' => 'https://www.maritime-executive.com',
                'content' => 'Selat Malaka sebagai salah satu selat tersibuk di dunia menampung lebih dari 85.000 kapal kargo per tahun. Memasuki kuartal ketiga 2026, fenomena anomali gelombang tinggi laut Andaman berpotensi menurunkan efisiensi kecepatan bongkar muat di Pelabuhan Singapura dan Belawan hingga 14%. Risk intel sarankan diversifikasi rute pelayaran dan peningkatan pemantauan real-time.'
            ],
            [
                'title' => 'Dampak Geopolitik Timur Tengah terhadap Biaya Asuransi Kargo Terusan Suez & Bab el-Mandeb',
                'author' => 'Dr. Hendra Maritim',
                'url' => 'https://www.lloydslist.com',
                'content' => 'Ketegangan geopolitik yang berkelanjutan di sekitar Laut Merah telah meningkatkan premi risiko perang (War Risk Premium) bagi kapal kontainer kargo global. Banyak armada utama memilih rute memutar melalui Tanjung Harapan (Cape of Good Hope), menambah waktu tempuh 10-14 hari serta meningkatkan konsumsi bahan bakar maritim secara signifikan.'
            ],
            [
                'title' => 'Evaluasi Kapasitas Logistik & Sentimen Ekonomi Pelabuhan Rotterdam dan Hamburg',
                'author' => 'Eurasia Supply Chain Unit',
                'url' => 'https://www.portstrategy.com',
                'content' => 'Perekonomian kawasan Eropa mengalami tekanan moderat akibat fluktuasi harga energi dan regulasi baru batas emisi karbon maritim Uni Eropa (EU ETS). Pelabuhan Rotterdam mulai mengimplementasikan sistem penjadwalan otomatis berbasis AI untuk mengurangi waktu tunggu (berthing delay) di terminal peti kemas utama.'
            ],
            [
                'title' => 'Studi Strategis: Integrasi Data Cuaca Open-Meteo dengan Pemodelan Skor Risiko Supply Chain',
                'author' => 'Divisi AI & Meteorologi',
                'url' => 'https://open-meteo.com',
                'content' => 'Pemanfaatan data satelit cuaca secara real-time seperti kecepatan angin, tinggi gelombang, dan probabilitas badai siklon memungkinkan sistem intelijen risiko menghitung skor kerentanan koridor laut secara dinamis. Integrasi otomatis ini membantu eksekutif logistik mengambil keputusan preventif sebelum badai melumpuhkan pelabuhan tujuan.'
            ],
            [
                'title' => 'Prospek Pertumbuhan Pelabuhan Tanjung Priok dan Patimban dalam Rantai Pasok ASEAN',
                'author' => 'Kajian Logistik Nasional',
                'url' => 'https://www.supplychainbrain.com',
                'content' => 'Pengembangan infrastruktur maritim di Indonesia menunjukkan tren positif dengan beroperasinya Pelabuhan Patimban secara penuh untuk ekspor otomotif dan logistik berat. Sinergi antara Tanjung Priok dan Patimban diproyeksikan mampu menekan biaya logistik nasional hingga 18% dalam rentang 3 tahun ke depan.'
            ],
        ];

        foreach ($articles as $article) {
            Article::firstOrCreate(
                ['title' => $article['title']],
                [
                    'author' => $article['author'],
                    'url' => $article['url'],
                    'content' => $article['content']
                ]
            );
        }
    }
}
