<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <!-- Sertakan stylesheet di sini jika diperlukan -->
</head>
    <style>
        /* Gaya CSS Anda dapat disesuaikan di sini */
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            width: 100%;
            margin: 0 auto;
        }

        .header {
            text-align: center;
        }

        .item {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            padding: 5px 0;
            text-align: center;
            font-weight: bold;
        }

        .total {
            font-weight: bold;
            border-top: 1px solid #000;
            padding: 5px 0;
        }

        .logo {
            text-align: center;
        }

        .logo img {
            max-width: 400px;
        }
    </style>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <img alt="GRAND HOTEL ATMA"
                    src="https://firebasestorage.googleapis.com/v0/b/capstone-cdb77.appspot.com/o/logo.png?alt=media&token=c134b6af-1e0d-434e-b381-dcd077196515">
            </div>
            <p>Jl. P. Mangkubumi No.18, Yogyakarta 55233</p>
            <p>Telp. (0274) 487711</p>
        </div>
        <hr>
        <h3 style="text-align: center">LAPORAN 5 CUSTOMER RESERVASI TERBANYAK</h3>
        <hr>
        <div>
            <h4>Tahun: {{ $tahun }}</h4>
        </div>

        <table style="width: 100%; padding-top : 5px;" border="2px">
            <tr class="item">
                <td>No</td>
                <td>Nama Customer</td>
                <td>Jumlah Reservasi</td>
                <td>Total Pembayaran</td>
            </tr>
            <?php $i = 1; ?>
            @foreach ($data as $item)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $item['customer_name'] }}</td>
                    <td style="text-align: right;">{{ $item['jumlah_reservasi'] }}</td>
                    <td style="text-align: right;">Rp{{ number_format($item['total_harga_kamar'] + $item['total_harga_fasilitas'])  }}</td>
                </tr>
            @endforeach
        </table>
        <div style="text-align: right;">
        <p>Dicetak tanggal : {{ $cetak }}</p>
        </div>
    </div>
</body>
</html>
