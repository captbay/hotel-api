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
        <h3 style="text-align: center">LAPORAN PENDAPATAN BULANAN</h3>
        <hr>
        <div>
            <h4>Tahun: {{ $tahun }}</h4>
        </div>

        <table style="width: 100%; padding-top : 5px;" border="2px">
            <tr class="item">
                <td>No</td>
                <td>Bulan</td>
                <td>Grup</td>
                <td>Personal</td>
                <td>Total</td>
            </tr>
            <?php $i = 1; ?>
            @foreach ($data as $item)
                <tr>
                    <td style="text-align: center;">{{ $i++ }}</td>
                    <td>{{ $item['bulan'] }}</td>
                    <td style="text-align: right;">Rp{{ number_format($item['grup']) }}</td>
                    <td style="text-align: right;">Rp{{ number_format($item['personal']) }}</td>
                    <td style="text-align: right;">Rp{{ number_format($item['total']) }}</td>
                </tr>
            @endforeach
            <tr class="total">
                <td colspan="4" style="text-align: right;">Total:</td>
                <td style="text-align: right;">Rp{{ number_format($total)}}</td>
            </tr>
        </table>
        <div style="text-align: right;">
        <p>Dicetak tanggal : {{ $cetak }}</p>
        </div>
    </div>
</body>
</html>
