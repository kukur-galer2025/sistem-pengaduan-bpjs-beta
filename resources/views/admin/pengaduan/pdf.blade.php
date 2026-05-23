<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pengaduan BPJS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #118AB2;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            color: #073B4C;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #118AB2;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .status {
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-menunggu { color: #d97706; }
        .status-diproses { color: #2563eb; }
        .status-selesai { color: #059669; }
        .status-ditolak { color: #dc2626; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Laporan Rekapitulasi Pengaduan Layanan BPJS</h2>
        <p>Periode: {{ date('F', mktime(0, 0, 0, $month, 1)) }} {{ $year }}</p>
        <p>Total Pengaduan: {{ $complaints->count() }} Laporan</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Kode Tiket / Tgl</th>
                <th width="20%">Data Pelapor</th>
                <th width="20%">Kategori</th>
                <th width="30%">Subjek Keluhan</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($complaints as $index => $complaint)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $complaint->kode_tiket }}</strong><br>
                    <small>{{ $complaint->created_at->format('d/m/Y') }}</small>
                </td>
                <td>
                    {{ $complaint->user->name }}<br>
                    <small>NIK: {{ $complaint->user->nik }}</small><br>
                    <small>BPJS: {{ $complaint->user->nomor_bpjs }}</small>
                </td>
                <td>{{ $complaint->category->nama_kategori }}</td>
                <td>{{ $complaint->judul }}</td>
                <td class="status status-{{ $complaint->status }}">
                    {{ $complaint->status }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">Tidak ada data pengaduan pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>