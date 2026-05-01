@extends('layouts.petugas')

@section('title', 'FAQ - Petugas')
@section('page-title', 'Frequently Asked Questions')
@section('page-subtitle', 'Pertanyaan yang sering diajukan')

@section('content')
<div class="panduan-detail">
    
    <a href="{{ route('petugas.panduan.index') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Kembali ke Panduan
    </a>
    
    <div class="detail-header">
        <div class="detail-icon" style="background: #EDE9FE;">
            <i class="fas fa-question-circle" style="color: #8B5CF6;"></i>
        </div>
        <div>
            <h1>Frequently Asked Questions</h1>
            <p>Pertanyaan yang sering diajukan seputar penggunaan sistem</p>
        </div>
    </div>
    
    <div class="faq-container">
        <div class="faq-item">
            <div class="faq-question">
                <i class="fas fa-question-circle"></i>
                <span>Apa yang harus dilakukan jika data nilai ditolak admin?</span>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <p>Jika data ditolak, Anda dapat:</p>
                <ol>
                    <li>Buka menu <strong>Riwayat Penilaian</strong></li>
                    <li>Cari data yang berstatus "Ditolak"</li>
                    <li>Klik tombol <strong>Edit</strong></li>
                    <li>Perbaiki nilai sesuai catatan dari admin</li>
                    <li>Simpan ulang data</li>
                </ol>
            </div>
        </div>
        
        <div class="faq-item">
            <div class="faq-question">
                <i class="fas fa-question-circle"></i>
                <span>Berapa lama proses validasi data?</span>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <p>Proses validasi data dilakukan oleh admin dalam waktu maksimal <strong>1x24 jam</strong> setelah data disimpan. Anda dapat memantau status validasi di menu Riwayat Penilaian.</p>
            </div>
        </div>
        
        <div class="faq-item">
            <div class="faq-question">
                <i class="fas fa-question-circle"></i>
                <span>Mengapa ada jalan yang tidak bisa dipilih saat input nilai?</span>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <p>Jalan yang sudah memiliki data lengkap dan sudah <strong>divalidasi</strong> tidak dapat dipilih lagi. Hal ini untuk menjaga integritas data yang sudah disetujui admin.</p>
            </div>
        </div>
        
        <div class="faq-item">
            <div class="faq-question">
                <i class="fas fa-question-circle"></i>
                <span>Bagaimana cara mengisi koordinat dengan cepat?</span>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <p>Anda dapat menggunakan tombol <strong>"Gunakan Lokasi Saya"</strong> pada form input/edit jalan. Tombol ini akan otomatis mengisi latitude dan longitude berdasarkan lokasi perangkat Anda.</p>
            </div>
        </div>
        
        <div class="faq-item">
            <div class="faq-question">
                <i class="fas fa-question-circle"></i>
                <span>Apa perbedaan antara tipe Benefit dan Cost?</span>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <ul>
                    <li><strong>Benefit:</strong> Semakin besar nilai, semakin baik (contoh: tingkat kerusakan, volume kendaraan)</li>
                    <li><strong>Cost:</strong> Semakin kecil nilai, semakin baik (contoh: biaya perbaikan)</li>
                </ul>
                <p>Perhatikan tipe ini saat mengisi nilai karena akan mempengaruhi perhitungan prioritas.</p>
            </div>
        </div>
        
        <div class="faq-item">
            <div class="faq-question">
                <i class="fas fa-question-circle"></i>
                <span>Bisakah data yang sudah disimpan dihapus?</span>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <p>Petugas <strong>tidak dapat menghapus data</strong> yang sudah disimpan. Hanya admin yang memiliki akses untuk menghapus data. Jika Anda perlu menghapus data, silakan hubungi admin.</p>
            </div>
        </div>
        
        <div class="faq-item">
            <div class="faq-question">
                <i class="fas fa-question-circle"></i>
                <span>Bagaimana cara mencetak laporan?</span>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <p>Untuk mencetak laporan, buka halaman yang ingin dicetak, lalu gunakan fitur <strong>Export </strong>yang tersedia (Excel, CSV, PDF). Anda juga dapat menggunakan shortcut keyboard <strong>Ctrl+P</strong> untuk mencetak halaman.</p>
            </div>
        </div>
    </div>
</div>

<style>
    .panduan-detail {
        max-width: 900px;
        margin: 0 auto;
    }
    
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: none;
        border: none;
        color: #F9A826;
        font-weight: 600;
        text-decoration: none;
        margin-bottom: 24px;
        cursor: pointer;
    }
    
    .detail-header {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 32px;
        padding-bottom: 20px;
        border-bottom: 2px solid #E2E8F0;
    }
    
    .detail-icon {
        width: 70px;
        height: 70px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .detail-header h1 {
        font-size: 24px;
        color: #1A2A3A;
        margin-bottom: 4px;
    }
    
    .detail-header p {
        color: #6B7280;
    }
    
    .faq-container {
        background: white;
        border-radius: 16px;
        border: 1px solid #E2E8F0;
        overflow: hidden;
    }
    
    .faq-item {
        border-bottom: 1px solid #E2E8F0;
    }
    
    .faq-item:last-child {
        border-bottom: none;
    }
    
    .faq-question {
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
        background: white;
        transition: all 0.2s;
    }
    
    .faq-question:hover {
        background: #F8FAFC;
    }
    
    .faq-question i:first-child {
        color: #F9A826;
        font-size: 18px;
    }
    
    .faq-question span {
        flex: 1;
        font-weight: 600;
        color: #1A2A3A;
    }
    
    .faq-question i:last-child {
        color: #9CA3AF;
        transition: transform 0.2s;
    }
    
    .faq-item.active .faq-question i:last-child {
        transform: rotate(180deg);
    }
    
    .faq-answer {
        display: none;
        padding: 0 20px 20px 52px;
        color: #6B7280;
        line-height: 1.6;
        background: #F8FAFC;
    }
    
    .faq-item.active .faq-answer {
        display: block;
    }
    
    .faq-answer ul, .faq-answer ol {
        margin-left: 20px;
        padding-left: 0;
    }
    
    .faq-answer li {
        margin-bottom: 8px;
    }
</style>

<script>
    document.querySelectorAll('.faq-question').forEach(question => {
        question.addEventListener('click', () => {
            const parent = question.parentElement;
            parent.classList.toggle('active');
        });
    });
</script>
@endsection