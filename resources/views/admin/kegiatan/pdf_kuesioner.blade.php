<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Evaluasi Kegiatan - {{ $user->username }}</title>
    <style>
        @page {
            margin: 10mm;
            size: A4 portrait;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 7.2pt;
            color: #000;
            line-height: 1.05;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }
        
        /* Natural flow border box with safety padding. Standard border stretches automatically! */
        .outer-border {
            border: 2px solid #000;
            padding: 10px;
            box-sizing: border-box;
            min-height: 267mm; /* Guarantees it fills the physical A4 sheet vertically! */
        }

        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .underline { text-decoration: underline; }
        
        /* Compressed Header */
        .header-table {
            width: 100%;
            margin-bottom: 4px;
        }
        .logo {
            width: 42px;
            height: auto;
            display: inline-block;
            margin-bottom: 1px;
        }
        .header-title {
            font-size: 10.5pt;
            font-weight: bold;
            text-decoration: underline;
            margin: 0;
            text-transform: uppercase;
        }
        .header-kegiatan {
            font-size: 9pt;
            font-weight: bold;
            color: #d90000;
            margin: 1px 0;
            text-transform: uppercase;
        }
        .header-instansi {
            font-size: 8.5pt;
            font-weight: bold;
            margin: 0;
        }
        .header-tahun {
            font-size: 8.5pt;
            font-weight: bold;
            margin: 0 0 3px 0;
        }

        /* Scale Subheader */
        .skala-box {
            text-align: center;
            font-size: 7.5pt;
            margin-bottom: 10px;
            border-top: 1.2px solid #000;
            border-bottom: 1.2px solid #000;
            padding: 3px 0;
            width: 100%;
        }

        /* Fixed layout table with spacer column in the middle for absolute separation */
        .layout-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .layout-table > tbody > tr > td {
            vertical-align: top;
            padding: 0;
        }
        .left-column {
            width: 47.5%;
        }
        .spacer-column {
            width: 5%;
        }
        .right-column {
            width: 47.5%;
        }

        /* Section Headings */
        .section-title {
            font-weight: bold;
            font-size: 8.5pt;
            text-transform: uppercase;
            text-decoration: underline;
            margin-top: 0;
            margin-bottom: 6px;
        }
        
        /* Matrix Items */
        .matrix-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 3px;
        }
        .matrix-table td {
            padding: 1.5px 0;
            vertical-align: middle;
            font-size: 7.5pt;
        }
        .col-label {
            width: 55%;
            white-space: nowrap;
            overflow: hidden;
        }
        .col-box {
            width: 9%;
            text-align: center;
        }
        
        /* Checkboxes */
        .c-box {
            display: inline-block;
            width: 11px;
            height: 11px;
            border: 1px solid #000;
            text-align: center;
            line-height: 10px;
            font-size: 7.5pt;
            font-weight: bold;
            vertical-align: middle;
            margin-right: 1px;
            background-color: #fff;
        }
        .c-num {
            font-size: 7pt;
            vertical-align: middle;
        }

        /* Compressed Comment Boxes */
        .comment-row-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 9px; /* Increased from 5px to stretch content vertically */
        }
        .comment-row-label {
            width: 20%;
            font-size: 7pt;
            vertical-align: middle;
            padding-left: 6px;
        }
        .comment-row-box {
            width: 80%;
            border: 0.8px solid #000;
            height: 12px;
            min-height: 12px;
            padding: 1px 3px;
            font-size: 7pt;
            line-height: 10px;
            overflow: hidden;
            background-color: #fff;
        }

        /* Overall Box */
        .overall-table {
            width: 100%;
            margin-top: 16px; /* Increased to align down */
            border-collapse: collapse;
        }
        .overall-title-cell {
            width: 55%;
            font-weight: bold;
            text-decoration: underline;
            vertical-align: top;
            padding-right: 4px;
            font-size: 7.8pt;
            line-height: 1.15;
        }
        .overall-options-cell {
            width: 45%;
            vertical-align: top;
        }
        .option-row {
            margin-bottom: 3px;
            font-size: 7.8pt;
        }
        .option-box {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 1.2px solid #000;
            text-align: center;
            line-height: 11px;
            font-weight: bold;
            margin-right: 4px;
            vertical-align: middle;
        }
        .option-text {
            vertical-align: middle;
        }

        /* Kritik & Saran Box */
        .saran-container {
            margin-top: 20px; /* Increased spacing */
        }
        .saran-title {
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 4px;
            font-size: 8pt;
        }
        .saran-text-area {
            width: 97%;
            border: 1.2px solid #000;
            height: 115px; /* Increased from 75px to consume the remaining bottom space perfectly */
            min-height: 115px;
            padding: 4px;
            font-size: 7.5pt;
            line-height: 1.15;
            overflow: hidden;
            background-color: #fff;
        }
    </style>
</head>
<body>

    @php
        $getRatingVal = function($category, $itemName) use ($kuesioner, $answers) {
            $q = $kuesioner->pertanyaan->first(function($p) use ($category, $itemName) {
                return (isset($p->opsi['kategori']) && $p->opsi['kategori'] === $category) && 
                       (trim(strtolower($p->pertanyaan)) === trim(strtolower($itemName)));
            });
            return $q ? ($answers[$q->id]->jawaban ?? null) : null;
        };

        $getCommentVal = function($category) use ($kuesioner, $answers) {
            $q = $kuesioner->pertanyaan->first(function($p) use ($category) {
                return (isset($p->opsi['kategori']) && $p->opsi['kategori'] === $category) && 
                       ($p->tipe === 'text');
            });
            return $q ? ($answers[$q->id]->jawaban ?? '') : '';
        };

        $overallRating = $getRatingVal('Penilaian Keseluruhan', 'Penilaian kegiatan');
        $kritikSaran = $getCommentVal('Penilaian Keseluruhan');
    @endphp

    <div class="outer-border">
        
        <!-- HEADER KOP -->
        <table class="header-table" style="text-align: center;">
            <tr>
                <td>
                    <img src="{{ public_path('img/logo-uin.png') }}" class="logo" alt="Logo UIN"><br>
                    <div class="header-title">EVALUASI KEGIATAN</div>
                    <div class="header-kegiatan">[{{ strtoupper($kegiatan->nama_kegiatan) }}]</div>
                    <div class="header-instansi">UNIVERSITAS ISLAM NEGERI SULTAN MAULANA HASANUDDIN BANTEN</div>
                    <div class="header-tahun">TAHUN ANGGARAN {{ $kegiatan->waktu_mulai->format('Y') }}</div>
                </td>
            </tr>
        </table>

        <!-- SKALA PENILAIAN -->
        <div class="skala-box">
            Skala Penilaian : 1 (Tidak Baik); 2 (Kurang Baik); 3 (Cukup); 4 (Baik); 5 (Sangat Baik)
        </div>

        <!-- MAIN 3-CELL TABLE WITH EXPLICIT MIDDLE SPACER FOR SAFETY -->
        <table class="layout-table">
            <tr>
                <!-- LEFT COLUMN -->
                <td class="left-column">
                    
                    <!-- I. NARASUMBER -->
                    <div class="section-title">I. NARASUMBER</div>
                    @php
                        $narasumberItems = ['Kompetensi', 'Penguasaan Materi', 'Cara penyampaian', 'Komunikasi', 'Ketepatan Waktu'];
                        $naraComment = $getCommentVal('Narasumber');
                    @endphp
                    @foreach($narasumberItems as $idx => $item)
                        @php $val = $getRatingVal('Narasumber', $item); @endphp
                        <table class="matrix-table">
                            <tr>
                                <td class="col-label">{{ $idx + 1 }} {{ $item }}</td>
                                @for($i = 1; $i <= 5; $i++)
                                    <td class="col-box">
                                        <div class="c-box">{{ (int)$val === $i ? 'X' : '' }}</div><span class="c-num">{{ $i }}</span>
                                    </td>
                                @endfor
                            </tr>
                        </table>
                        <table class="comment-row-table">
                            <tr>
                                <td class="comment-row-label">Komentar :</td>
                                <td class="comment-row-box">{{ $idx === 0 ? $naraComment : '' }}</td>
                            </tr>
                        </table>
                    @endforeach

                    <!-- II. MATERI YANG DISAMPAIKAN -->
                    <div class="section-title" style="margin-top: 14px;">II. MATERI YANG DISAMPAIKAN</div>
                    @php
                        $materiItems = ['Kesesuaian Kegiatan', 'Kesesuaian Kebutuhan', 'Kesesuaian Peserta', 'Alokasi Waktu Materi', 'Ketersediaan Materi'];
                        $materiComment = $getCommentVal('Materi yang disampaikan');
                    @endphp
                    @foreach($materiItems as $idx => $item)
                        @php $val = $getRatingVal('Materi yang disampaikan', $item); @endphp
                        <table class="matrix-table">
                            <tr>
                                <td class="col-label">{{ $idx + 1 }} {{ $item }}</td>
                                @for($i = 1; $i <= 5; $i++)
                                    <td class="col-box">
                                        <div class="c-box">{{ (int)$val === $i ? 'X' : '' }}</div><span class="c-num">{{ $i }}</span>
                                    </td>
                                @endfor
                            </tr>
                        </table>
                        <table class="comment-row-table">
                            <tr>
                                <td class="comment-row-label">Komentar :</td>
                                <td class="comment-row-box">{{ $idx === 0 ? $materiComment : '' }}</td>
                            </tr>
                        </table>
                    @endforeach

                    <!-- III. AKOMODASI / TEMPAT KEGIATAN -->
                    <div class="section-title" style="margin-top: 14px;">III. AKOMODASI / TEMPAT KEGIATAN</div>
                    @php
                        $akomodasiItems = ['Kelengkapan / Fasilitas', 'Kenyamanan', 'Kebersihan', 'Keamanan', 'Pelayanan'];
                        $akoComment = $getCommentVal('Akomodasi / Tempat Kegiatan');
                    @endphp
                    @foreach($akomodasiItems as $idx => $item)
                        @php $val = $getRatingVal('Akomodasi / Tempat Kegiatan', $item); @endphp
                        <table class="matrix-table">
                            <tr>
                                <td class="col-label">{{ $idx + 1 }} {{ $item }}</td>
                                @for($i = 1; $i <= 5; $i++)
                                    <td class="col-box">
                                        <div class="c-box">{{ (int)$val === $i ? 'X' : '' }}</div><span class="c-num">{{ $i }}</span>
                                    </td>
                                @endfor
                            </tr>
                        </table>
                        <table class="comment-row-table">
                            <tr>
                                <td class="comment-row-label">Komentar :</td>
                                <td class="comment-row-box">{{ $idx === 0 ? $akoComment : '' }}</td>
                            </tr>
                        </table>
                    @endforeach

                </td>

                <!-- MIDDLE SPACER BUFFER -->
                <td class="spacer-column"></td>

                <!-- RIGHT COLUMN -->
                <td class="right-column">
                    
                    <!-- IV. KONSUMSI -->
                    <div class="section-title">IV. KONSUMSI</div>
                    @php
                        $konsumsiItems = ['Kualitas', 'Kuantitas', 'Variasi', 'Kebersihan', 'Pelayanan'];
                        $konsComment = $getCommentVal('Konsumsi');
                    @endphp
                    @foreach($konsumsiItems as $idx => $item)
                        @php $val = $getRatingVal('Konsumsi', $item); @endphp
                        <table class="matrix-table">
                            <tr>
                                <td class="col-label">{{ $idx + 1 }} {{ $item }}</td>
                                @for($i = 1; $i <= 5; $i++)
                                    <td class="col-box">
                                        <div class="c-box">{{ (int)$val === $i ? 'X' : '' }}</div><span class="c-num">{{ $i }}</span>
                                    </td>
                                @endfor
                            </tr>
                        </table>
                        <table class="comment-row-table">
                            <tr>
                                <td class="comment-row-label">Komentar :</td>
                                <td class="comment-row-box">{{ $idx === 0 ? $konsComment : '' }}</td>
                            </tr>
                        </table>
                    @endforeach

                    <!-- V. PELAYANAN PANITIA -->
                    <div class="section-title" style="margin-top: 14px;">V. PELAYANAN PANITIA</div>
                    @php
                        $panitiaItems = ['Layanan Administrasi', 'Keramahan', 'Kesigapan', 'Penampilan', 'Kekompakkan'];
                        $panComment = $getCommentVal('Pelayanan Panitia');
                    @endphp
                    @foreach($panitiaItems as $idx => $item)
                        @php $val = $getRatingVal('Pelayanan Panitia', $item); @endphp
                        <table class="matrix-table">
                            <tr>
                                <td class="col-label">{{ $idx + 1 }} {{ $item }}</td>
                                @for($i = 1; $i <= 5; $i++)
                                    <td class="col-box">
                                        <div class="c-box">{{ (int)$val === $i ? 'X' : '' }}</div><span class="c-num">{{ $i }}</span>
                                    </td>
                                @endfor
                            </tr>
                        </table>
                        <table class="comment-row-table">
                            <tr>
                                <td class="comment-row-label">Komentar :</td>
                                <td class="comment-row-box">{{ $idx === 0 ? $panComment : '' }}</td>
                            </tr>
                        </table>
                    @endforeach

                    <!-- VI. SECARA KESELURUHAN -->
                    <table class="overall-table">
                        <tr>
                            <td class="overall-title-cell">
                                Secara keseluruhan, penilaian Anda terhadap penyelenggaraan kegiatan ini:
                            </td>
                            <td class="overall-options-cell">
                                <div class="option-row">
                                    <div class="option-box">{{ (int)$overallRating === 5 ? 'X' : '' }}</div>
                                    <span class="option-text">Sangat Baik</span>
                                </div>
                                <div class="option-row">
                                    <div class="option-box">{{ (int)$overallRating === 4 ? 'X' : '' }}</div>
                                    <span class="option-text">Baik</span>
                                </div>
                                <div class="option-row">
                                    <div class="option-box">{{ (int)$overallRating === 3 ? 'X' : '' }}</div>
                                    <span class="option-text">Cukup</span>
                                </div>
                                <div class="option-row">
                                    <div class="option-box">{{ (int)$overallRating === 2 ? 'X' : '' }}</div>
                                    <span class="option-text">Kurang Baik</span>
                                </div>
                                <div class="option-row">
                                    <div class="option-box">{{ (int)$overallRating === 1 ? 'X' : '' }}</div>
                                    <span class="option-text">Tidak Baik</span>
                                </div>
                            </td>
                        </tr>
                    </table>

                    <!-- VII. KRITIK DAN SARAN -->
                    <div class="saran-container">
                        <div class="saran-title">KRITIK DAN SARAN</div>
                        <div class="saran-text-area">
                            {{ $kritikSaran ?: '' }}
                        </div>
                    </div>

                </td>
            </tr>
        </table>
    </div>

</body>
</html>
