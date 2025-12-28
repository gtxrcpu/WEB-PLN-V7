<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $type === 'kartu' ? 'Rekap Kartu Kendali' : 'Rekap Peralatan' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #4F46E5;
            color: white;
            padding: 8px 5px;
            text-align: left;
            font-weight: bold;
            font-size: 9px;
        }
        td {
            padding: 6px 5px;
            border-bottom: 1px solid #ddd;
            font-size: 9px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
        .status-baik {
            color: #10B981;
            font-weight: bold;
        }
        .status-rusak {
            color: #EF4444;
            font-weight: bold;
        }
        .status-approved {
            color: #10B981;
            font-weight: bold;
        }
        .status-pending {
            color: #F59E0B;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $type === 'kartu' ? 'REKAP KARTU KENDALI' : 'REKAP PERALATAN PEMADAM KEBAKARAN' }}</h1>
        <p>PT PLN (Persero)</p>
        <p>Tanggal: {{ $date }}</p>
        <p>Modul: {{ strtoupper($module) }}</p>
    </div>

    @if($type === 'kartu')
        <table>
            <thead>
                <tr>
                    <th style="width: 3%;">No</th>
                    <th style="width: 10%;">Modul</th>
                    <th style="width: 10%;">Serial No</th>
                    <th style="width: 10%;">Tgl Periksa</th>
                    <th style="width: 12%;">Kesimpulan</th>
                    <th style="width: 15%;">Dibuat Oleh</th>
                    <th style="width: 12%;">Tgl Dibuat</th>
                    <th style="width: 10%;">Status</th>
                    <th style="width: 15%;">Di-approve Oleh</th>
                    <th style="width: 13%;">Tgl Approval</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item['modul'] }}</td>
                        <td>{{ $item['serial_no'] }}</td>
                        <td>{{ $item['tgl_periksa'] }}</td>
                        <td>{{ $item['kesimpulan'] }}</td>
                        <td>{{ $item['dibuat_oleh'] }}</td>
                        <td>{{ $item['tgl_dibuat'] }}</td>
                        <td class="{{ strtolower($item['status']) === 'approved' ? 'status-approved' : 'status-pending' }}">
                            {{ $item['status'] }}
                        </td>
                        <td>{{ $item['approved_oleh'] }}</td>
                        <td>{{ $item['tgl_approval'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Modul</th>
                    <th>Serial No</th>
                    <th>Barcode</th>
                    <th>Lokasi</th>
                    <th>Status</th>
                    <th>Kapasitas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item['modul'] }}</td>
                        <td>{{ $item['serial_no'] }}</td>
                        <td>{{ $item['barcode'] }}</td>
                        <td>{{ $item['lokasi'] }}</td>
                        <td class="{{ strtolower($item['status']) === 'baik' ? 'status-baik' : 'status-rusak' }}">
                            {{ strtoupper($item['status']) }}
                        </td>
                        <td>{{ $item['kapasitas'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        <p>Total: {{ count($data) }} {{ $type === 'kartu' ? 'kartu' : 'unit' }}</p>
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
