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
        <h3 style="text-align: center">INVOICE</h3>
        <hr>
        <div style="text-align: right">
            <p>Tanggal: {{ $data['tanggal'] }}</p>
            <p>No. Invoice: {{ $data['nomor_invoice'] }}</p>
            <p>Front Office: {{ $data['front_office'] }}</p>
        </div>
        <hr>
        <h3 style="text-align: center">DETAIL</h3>
        <hr>
        <!-- Informasi Pelanggan -->
        <p>ID Booking: {{ $data['id_booking'] }}</p>
        <p>Nama: {{ $data['nama_pelanggan'] }}</p>
        <p>Alamat: {{ $data['alamat'] }}</p>

        <!-- Detail Pemesanan -->
        <p>Check In: {{ $data['check_in'] }}</p>
        <p>Check Out: {{ $data['check_out'] }}</p>
        <p>Dewasa: {{ $data['dewasa'] }}</p>
        <p>Anak-anak: {{ $data['anak_anak'] }}</p>

        <!-- Kamar -->
        <hr>
        <h3 style="text-align: center">KAMAR</h3>
        <hr>
        <table style="width: 100%; padding-top : 5px;" border="2px">
            <tr class="item">
                <td>Jenis Kamar</td>
                <td>Bed</td>
                <td>Jumlah</td>
                <td>Harga</td>
                <td>Sub Total</td>
            </tr>
            @foreach ($data['kamar'] as $kamar)
                <tr>
                    <td>{{ $kamar['jenis_kamar'] }}</td>
                    <td>{{ $kamar['bed'] }}</td>
                    <td style="text-align: right;">{{ $kamar['jumlah'] }}</td>
                    <td style="text-align: right;">Rp{{ number_format($kamar['harga']) }}</td>
                    <td style="text-align: right;">Rp{{ number_format($kamar['sub_total']) }}</td>
                </tr>
            @endforeach
            <tr class="total">
                <td colspan="4" style="text-align: right;">Total:</td>
                <td style="text-align: right;">Rp{{ number_format($data['total_harga_kamar']) }}</td>
            </tr>
        </table>
        <!-- Layanan -->
        <hr>
        <h3 style="text-align: center">LAYANAN</h3>
        <hr>
        <table style="width: 100%;" border="2px">
            <tr class="item">
                <td>Layanan</td>
                <td>Tanggal</td>
                <td>Jumlah</td>
                <td>Harga</td>
                <td>Sub Total</td>
            </tr>
            @foreach ($data['layanan'] as $layanan)
                <tr>
                    <td>{{ $layanan['layanan'] }}</td>
                    <td style="text-align: right;">{{ $layanan['tanggal'] }}</td>
                    <td style="text-align: right;">{{ $layanan['jumlah'] }}</td>
                    <td style="text-align: right;">Rp{{ number_format($layanan['harga']) }}</td>
                    <td style="text-align: right;">Rp{{ number_format($layanan['sub_total']) }}</td>
                </tr>
            @endforeach
            <tr class="total">
                <td colspan="4" style="text-align: right;">Total:</td>
                <td style="text-align: right;">Rp{{ number_format($data['total_harga_fasilitas']) }}</td>
            </tr>
        </table>
        <div style="text-align: right;">
        <p>Pajak: Rp{{ number_format($data['pajak']) }}</p>
        <h4>Total: Rp{{ number_format($data['total']) }}</h4>
        <br>
        <p>Jaminan: Rp{{ number_format($data['jaminan']) }}</p>
        <p>Deposit: Rp{{ number_format($data['deposit']) }}</p>
        <h4>Cash: Rp{{ number_format($data['tunai']) }}</h4>
        </div>
        <p style="text-align: center;">Thank You For Your Visit!</p>
    </div>
</body>
</html>
