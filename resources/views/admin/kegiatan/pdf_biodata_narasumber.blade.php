<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Biodata Narasumber</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.3;
            margin: 0;
            padding: 10px 20px;
        }
        .page-break {
            page-break-after: always;
        }
        .text-center {
            text-align: center;
        }
        .header {
            margin-bottom: 15px;
        }
        .header h3 {
            margin: 0;
            font-size: 11pt;
            font-weight: bold;
        }
        .header h2 {
            margin: 0;
            font-size: 13pt;
            font-weight: bold;
        }
        .logo {
            width: 75px;
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        td {
            padding: 3px 0;
            vertical-align: top;
        }
        .label {
            width: 200px;
        }
        .colon {
            width: 20px;
            text-align: center;
        }
        .value {
            border-bottom: 1px dotted #000;
        }
        .signature-area {
            width: 100%;
            margin-top: 30px;
            page-break-inside: avoid;
        }
        .signature-box {
            float: right;
            width: 300px;
            text-align: center;
            page-break-inside: avoid;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>

    @php
        if (!function_exists('val')) {
            function val($data) {
                return !empty($data) ? $data : '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            }
        }
    @endphp
    @foreach($narasumber as $index => $n)
        @php
            $bio = $n->biodata;
        @endphp

        <div class="{{ !$loop->last ? 'page-break' : '' }}">
            
            <div class="header text-center">
                <img src="{{ public_path('img/logo-uin.png') }}" class="logo" alt="Logo UIN">
                <h3>BIODATA NARASUMBER</h3>
                <h3>{{ strtoupper($kegiatan->nama_kegiatan) }}</h3>
                <h3>UNIVERSITAS ISLAM NEGERI SULTAN MAULANA HASANUDDIN BANTEN</h3>
                <h3>TAHUN ANGGARAN {{ date('Y') }}</h3>
            </div>

            <table>
                <tr>
                    <td class="label">Nama</td>
                    <td class="colon">:</td>
                    <td class="value">{!! val($bio->nama_lengkap ?? null) !!}</td>
                </tr>
                <tr>
                    <td class="label">NIP</td>
                    <td class="colon">:</td>
                    <td class="value">{!! val($bio->nip ?? null) !!}</td>
                </tr>
                <tr>
                    <td class="label">Pangkat (Gol/Ruang)</td>
                    <td class="colon">:</td>
                    <td class="value">{!! val($bio->pangkat_golongan ?? null) !!}</td>
                </tr>
                <tr>
                    <td class="label">Tempat, Tanggal Lahir</td>
                    <td class="colon">:</td>
                    <td class="value">
                        @if(!empty($bio->tempat_lahir) && !empty($bio->tanggal_lahir))
                            {{ $bio->tempat_lahir }}, {{ \Carbon\Carbon::parse($bio->tanggal_lahir)->translatedFormat('d F Y') }}
                        @elseif(!empty($bio->tempat_lahir))
                            {{ $bio->tempat_lahir }}
                        @elseif(!empty($bio->tanggal_lahir))
                            {{ \Carbon\Carbon::parse($bio->tanggal_lahir)->translatedFormat('d F Y') }}
                        @else
                            {!! val('') !!}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="label">Unit Kerja / Bagian</td>
                    <td class="colon">:</td>
                    <td class="value">{!! val($bio->bagian ?? null) !!}</td>
                </tr>
                <tr>
                    <td class="label">Jabatan Terakhir</td>
                    <td class="colon">:</td>
                    <td class="value">{!! val($bio->jabatan ?? null) !!}</td>
                </tr>
                <tr>
                    <td class="label">Pendidikan</td>
                    <td class="colon">:</td>
                    <td>
                        <table style="margin-bottom:0;">
                            <tr>
                                <td style="width: 50px; padding:0; border:none;">1. S1</td>
                                <td style="width: 15px; padding:0; border:none;">:</td>
                                <td style="padding:0; border-bottom: 1px dotted #000;">{!! val($bio->pendidikan_s1 ?? null) !!}</td>
                            </tr>
                            <tr>
                                <td style="padding:0; padding-top:10px; border:none;">2. S2</td>
                                <td style="padding:0; padding-top:10px; border:none;">:</td>
                                <td style="padding:0; padding-top:10px; border-bottom: 1px dotted #000;">{!! val($bio->pendidikan_s2 ?? null) !!}</td>
                            </tr>
                            <tr>
                                <td style="padding:0; padding-top:10px; border:none;">3. S3</td>
                                <td style="padding:0; padding-top:10px; border:none;">:</td>
                                <td style="padding:0; padding-top:10px; border-bottom: 1px dotted #000;">{!! val($bio->pendidikan_s3 ?? null) !!}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="label">No Rekening</td>
                    <td class="colon">:</td>
                    <td class="value">{!! val($bio->no_rekening ?? null) !!}</td>
                </tr>
                <tr>
                    <td class="label">NPWP</td>
                    <td class="colon">:</td>
                    <td class="value">{!! val($bio->npwp ?? null) !!}</td>
                </tr>
                <tr>
                    <td class="label">Alamat Kantor</td>
                    <td class="colon">:</td>
                    <td class="value">{!! val($bio->alamat_kantor ?? null) !!}</td>
                </tr>
                <tr>
                    <td class="label">Alamat Rumah</td>
                    <td class="colon">:</td>
                    <td class="value">{!! val($bio->alamat_rumah ?? null) !!}</td>
                </tr>
                <tr>
                    <td class="label">HandPhone</td>
                    <td class="colon">:</td>
                    <td class="value">{!! val($bio->no_hp ?? null) !!}</td>
                </tr>
                <tr>
                    <td class="label">Alamat Email</td>
                    <td class="colon">:</td>
                    <td class="value">{!! val($bio->email ?? null) !!}</td>
                </tr>
            </table>

            <div class="clearfix signature-area">
                <div class="signature-box">
                    <p>Serang, &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</p>
                    <br><br><br><br>
                    <p style="margin: 0; padding-top: 5px;">(......................................................)</p>
                </div>
            </div>

        </div>
    @endforeach

</body>
</html>
