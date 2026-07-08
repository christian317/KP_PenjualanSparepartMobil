<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Resi Pengiriman - {{ $pesanan->nomor }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .label-container {
            border: 2px dashed #000;
            padding: 15px;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .border-bottom {
            border-bottom: 1px solid #000;
            padding-bottom: 8px;
            margin-bottom: 8px;
        }
        .text-muted { color: #555; }
        .small { font-size: 9px; }
        .fw-bold { font-weight: bold; }
        .mt-1 { margin-top: 3px; }
        .text-end { text-align: right; }
        .text-center { text-align: center; }

        /* Tabel Layout untuk Pengirim & Penerima */
        .info-table td { vertical-align: top; width: 50%; padding-bottom: 8px; }
        .info-table .border-right { border-right: 1px solid #000; padding-right: 10px; }
        .info-table .padding-left { padding-left: 10px; }

        /* Tabel Produk */
        .items-table th { border-top: 2px solid #000; border-bottom: 2px solid #000; padding: 5px 0; text-align: left; }
        .items-table td { padding: 5px 0; border-bottom: 1px dashed #ccc; vertical-align: top; }
    </style>
</head>

<body>
    <div class="label-container">
        
        <!-- Header Resi -->
        <table class="border-bottom">
            <tr>
                <td>
                    <div class="fw-bold" style="font-size: 14px;">RESI PENGIRIMAN</div>
                    <div class="fw-bold" style="font-size: 16px;">No Resi: {{ $pesanan->nomor }}</div>
                </td>
            </tr>
        </table>

        <!-- Info Pengirim dan Penerima (Menggunakan Tabel agar sejajar di PDF) -->
        <table class="info-table border-bottom">
            <tr>
                <td class="border-right">
                    <div class="small fw-bold text-muted mb-1">PENGIRIM:</div>
                    <div class="fw-bold">CV. Jaya Abadi Sparepart Mobil</div>
                    <div>081122334455</div>
                    <div class="small">Bandung, Jawa Barat</div>
                </td>
                <td class="padding-left">
                    <div class="small fw-bold text-muted mb-1">PENERIMA:</div>
                    <div class="fw-bold" style="font-size: 13px;">
                        {{ $pesanan->UserPelanggan->nama }}
                        @if($pesanan->UserPelanggan->nama_toko)
                            <br><span style="font-weight: normal; font-size: 11px;">({{ $pesanan->UserPelanggan->nama_toko }})</span>
                        @endif
                    </div>
                    <div class="fw-bold mt-1">{{ $pesanan->UserPelanggan->telepon }}</div>
                    <div class="small mt-1">{{ $pesanan->UserPelanggan->alamat }}</div>
                </td>
            </tr>
        </table>

        <!-- Info Tanggal dan Total Barang -->
        <table class="border-bottom">
            <tr>
                <td style="width: 50%;">
                    <span class="small fw-bold text-muted">Tanggal Cetak:</span><br>
                    <span class="fw-bold">{{ \Carbon\Carbon::now()->format('d M Y, H:i') }}</span>
                </td>
                <td style="width: 50%;" class="text-end">
                    <span class="small fw-bold text-muted">Total Qty:</span><br>
                    <span class="fw-bold" style="font-size: 13px;">{{ $pesanan->items->sum('jumlah') }} Item</span>
                </td>
            </tr>
        </table>

        <!-- Catatan Pembeli (Jika ada) -->
        @if ($pesanan->catatan)
            <div style="border: 1px solid #000; padding: 8px; margin-bottom: 10px;">
                <div class="small fw-bold text-muted">CATATAN PEMBELI:</div>
                <div class="fw-bold">{{ $pesanan->catatan }}</div>
            </div>
        @endif

        <!-- Rincian Produk -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 50%;">Nama Produk</th>
                    <th style="width: 30%;">SKU</th>
                    <th class="text-center" style="width: 15%;">Qty</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pesanan->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div class="fw-bold">
                                {{ $item->nama ?? ($item->produk ? $item->produk->nama : 'Produk Tidak Ditemukan') }}
                            </div>
                        </td>
                        <td>{{ $item->produk_id }}</td>
                        <td class="text-center fw-bold">{{ $item->jumlah }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
    </div>
</body>
</html>