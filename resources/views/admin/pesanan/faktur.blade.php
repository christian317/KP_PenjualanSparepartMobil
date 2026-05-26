<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Faktur - {{ $pesanan->nomor }}</title>
    <style>
        @page {
            size: A5 landscape;
            margin: 10mm 15mm;
        }
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 11pt;
            color: #000;
            line-height: 1.4;
            background-color: #fcfadd; 
            margin: 0;
            padding: 1 rem;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
        }
        td, th { 
            vertical-align: top; 
            text-align: left; 
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        .header-table { margin-bottom: 15px; }
        .header-table td { padding: 2px 0; }
        
        .items-table th { 
            padding: 8px 0;
            border-top: 1px dashed #000; 
            border-bottom: 1px dashed #000; 
            font-weight: normal; 
        }
        .items-table td { 
            padding: 6px 0;
        }
        
        .totals { margin-top: 10px; }
        .totals td { 
            padding: 8px 0; 
            border-top: 1px dashed #000; 
            border-bottom: 1px dashed #000; 
        }
        .signature { margin-top: 40px; }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td width="60%">
                <table style="width:100%">
                    <tr>
                        <td width="20%">No.Trans</td>
                        <td width="2%">:</td>
                        <td>{{ $pesanan->nomor }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td>:</td>
                        <td>{{ \Carbon\Carbon::parse($pesanan->tanggal)->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td>Jam</td>
                        <td>:</td>
                        <td>{{ \Carbon\Carbon::parse($pesanan->tanggal)->format('H:i:s') }}</td>
                    </tr>
                    <tr>
                        <td>Yth</td>
                        <td>:</td>
                        <td>{{ $pesanan->nama }}, (Ditempat)</td>
                    </tr>
                </table>
            </td>
            <td width="40%" class="text-right">
                <strong style="font-size:14pt;">CV. Jaya Abadi</strong><br>
                Jl. Raya Otomotif No. 123<br>
                TELP. 081122334455<br>
                HP. 081122334455
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="10%">Kode</th>
                <th width="45%">Nama</th>
                <th width="10%" class="text-center">Qty</th>
                <th width="20%" class="text-right">Harga Satuan</th>
                <th width="20%" class="text-right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}.</td>
                <td class="text-muted">{{ $item->produk_id }}</td>
                <td>{{ $item->nama_produk }}</td>
                <td class="text-center">{{ $item->jumlah }}</td>
                <td class="text-right">{{ number_format($item->harga, 0, '.', ',') }}</td>
                <td class="text-right">{{ number_format($item->harga * $item->jumlah, 0, '.', ',') }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="5" style="height: 15px;"></td>
            </tr>
        </tbody>
    </table>
    
    {{-- TOTAL --}}
    <table class="totals">
        <tr>
            <td width="55%"></td>
            <td width="15%">TOTAL</td>
            <td width="5%">: Rp</td>
            <td width="25%" class="text-right">{{ number_format($total_harga, 0, '.', ',') }}</td>
        </tr>
    </table>

    <div style="margin-top: 10px;">
        Terimakasih Atas Kepercayaan Anda Kepada Kami.
    </div>

    <table class="signature">
        <tr>
            <td width="50%" class="text-center">
                {{ $pesanan->nama }},<br><br><br><br>
                __________________
            </td>
            <td width="50%" class="text-center">
                Hormat Kami,<br><br><br><br>
                __________________<br>
                CV. Jaya Abadi
            </td>
        </tr>
    </table>

</body>
</html>