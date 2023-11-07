<!DOCTYPE html>
<html>

<head>
    <title>Tanda Terima Reservasi untuk {{ $reservasi->customer->name }}</title>
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

        .content {
            margin-top: 10px;
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
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <img
                    src="https://firebasestorage.googleapis.com/v0/b/capstone-cdb77.appspot.com/o/logo.png?alt=media&token=c134b6af-1e0d-434e-b381-dcd077196515">
            </div>
            <p>Jl. P. Mangkubumi No.18, Yogyakarta 55233</p>
            <p>Telp. (0274) 487711</p>
        </div>
        <div class="content">
            <div class="item">
                <span>TANDA TERIMA PEMESANAN</span>
            </div>
        </div>
        <div class="footer">
            <div>
                <table>
                    <tr>
                        <td>ID Booking </td>
                        <td>: {{ $reservasi->kode_booking }} </td>
                    </tr>
                    <tr>
                        <td>Tanggal </td>
                        <td>: {{ $tanggal_sekarang }} </td>
                    </tr>
                    @if ($reservasi->pegawai_id != null)
                        <tr>
                            <td>PIC</td>
                            <td>: {{ $reservasi->pegawai->name }}</td>
                        </tr>
                    @endif
                </table>
            </div>
            <table style="margin-top: 8px;">
                <tr>
                    <td>Nama</td>
                    <td>: {{ $reservasi->customer->name }}</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>: {{ $reservasi->customer->address }}</td>
                </tr>
            </table>
        </div>
        <div class="content">
            <div class="item">
                <span>DETAIL PEMESANAN</span>
            </div>
        </div>
        <div class="footer">
            <div>
                <table>
                    <tr>
                        <td>Check In</td>
                        <td>: {{ $reservasi->tanggal_reservasi }} </td>
                    </tr>
                    <tr>
                        <td>Check Out</td>
                        <td>: {{ $reservasi->tanggal_end_reservasi }} </td>
                    </tr>
                    <tr>
                        <td>Dewasa</td>
                        <td>: {{ $reservasi->dewasa }}</td>
                    </tr>
                    <tr>
                        <td>Anak-anak</td>
                        <td>: {{ $reservasi->anak }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Pembayaran</td>
                        <td>: {{ $reservasi->tanggal_pembayaran_lunas }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="content">
            <div class="item">
                <span><br/></span>
            </div>
        </div>
        <div style="margin-top: 8px">
            <table style="border: 1px solid black; border-collapse: collapse;">
                <tr>
                    <th style="border: 1px solid black; border-collapse: collapse; padding: 5px;">Jenis Kamar</th>
                    <th style="border: 1px solid black; border-collapse: collapse; padding: 5px;">Bed</th>
                    <th style="border: 1px solid black; border-collapse: collapse; padding: 5px;">Jumlah</th>
                    <th style="border: 1px solid black; border-collapse: collapse; padding: 5px;">Harga</th>
                    <th style="border: 1px solid black; border-collapse: collapse; padding: 5px;">Total</th>
                </tr>
                @foreach ($reservasi->transaksi_kamar as $kamar)
                    <tr>
                        <td style="border: 1px solid black; border-collapse: collapse; padding: 5px;"> {{$kamar->kamar->jenis_kamar['name']}} </td>
                        <td style="border: 1px solid black;  border-collapse: collapse; padding: 5px;"> {{$kamar->kamar->jenis_kamar['bed']}} </td>
                        <td style="border: 1px solid black; border-collapse: collapse; padding: 5px;"> 1 </td>
                        <td style="border: 1px solid black; border-collapse: collapse; padding: 5px;"> {{number_format($kamar->total_harga, 0, ',', '.')}} </td>
                        <td style="border: 1px solid black; border-collapse: collapse; padding: 5px;"> {{number_format($kamar->total_harga, 0, ',', '.')}} </td>
                    </tr>
                @endforeach
    
                <tr>
                    <td colspan="4" style="text-align: right; border: 1px solid black; padding: 5px;">Total</td>
                    <td style="border: 1px solid black; border-collapse: collapse; padding: 5px;">{{ number_format($reservasi->total_harga, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>
        <div class="footer" style="margin-top: 32px">
            <div>
                <table>
                    <tr>
                        <td>Permintaan Khusus :</td>
                    </tr>
                    <tr>
                        <td>{{ $reservasi->note }} </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
