<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Struk - {{ $transaction->invoice_code }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* RESET & BASIC */
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Courier New', Courier, monospace;
            background-color: #f3f4f6; /* Warna latar layar monitor */
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        /* TAMPILAN KERTAS STRUK (CONTAINER) */
        .receipt-container {
            width: 58mm; /* Lebar kertas thermal 58mm */
            background-color: #fff;
            padding: 15px 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1); /* Efek bayangan kertas */
            margin-bottom: 20px;
        }

        /* HEADER */
        .header {
            text-align: center;
            margin-bottom: 15px;
        }
        .store-name {
            font-size: 16px;
            font-weight: 800;
            text-transform: uppercase;
            margin: 0;
            line-height: 1.2;
        }
        .store-address {
            font-size: 10px;
            margin-top: 5px;
            color: #333;
        }
        .transaction-info {
            font-size: 10px;
            margin-top: 8px;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            padding: 5px 0;
            text-align: left;
        }

        /* ITEM LIST */
        .item-list {
            font-size: 11px;
            width: 100%;
            margin-top: 10px;
        }
        .item {
            margin-bottom: 5px;
        }
        .item-name {
            font-weight: bold;
            display: block;
            margin-bottom: 2px;
        }
        .item-details {
            display: flex;
            justify-content: space-between;
        }

        /* TOTAL SECTION */
        .total-section {
            margin-top: 10px;
            border-top: 1px dashed #000;
            padding-top: 5px;
            text-align: right;
            font-size: 12px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            margin-top: 2px;
        }
        .grand-total {
            font-size: 14px;
            font-weight: 900;
        }

        /* FOOTER */
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 10px;
            color: #555;
        }
        .footer p {
            margin: 0;
        }

        /* BUTTONS STYLING (MODERN) */
        .nav-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
            justify-content: center;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            padding: 10px 20px;
            border-radius: 50px; /* Tombol bulat */
            text-decoration: none;
            font-family: sans-serif; /* Font tombol beda dengan struk */
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .btn i {
            margin-right: 8px;
        }
        
        /* Warna Tombol */
        .btn-print {
            background-color: #2563eb; /* Biru */
            color: white;
        }
        .btn-print:hover { background-color: #1d4ed8; transform: translateY(-2px); }

        .btn-new {
            background-color: #16a34a; /* Hijau */
            color: white;
        }
        .btn-new:hover { background-color: #15803d; transform: translateY(-2px); }

        .btn-history {
            background-color: #ffffff;
            color: #374151;
            border: 1px solid #d1d5db;
        }
        .btn-history:hover { background-color: #f3f4f6; transform: translateY(-2px); }


        /* PENGATURAN CETAK (PRINT) - SANGAT PENTING */
        @media print {
            body {
                background-color: white;
                padding: 0;
                margin: 0;
                display: block; /* Reset display flex */
            }
            .no-print {
                display: none !important; /* Sembunyikan tombol saat print */
            }
            .receipt-container {
                width: 100%; /* Gunakan lebar penuh kertas */
                box-shadow: none; /* Hilangkan bayangan */
                margin: 0;
                padding: 0;
            }
            /* Hilangkan header/footer bawaan browser jika memungkinkan */
            @page {
                margin: 0;
                size: auto;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="nav-buttons no-print">
        <button onclick="window.print()" class="btn btn-print">
            <i class="fa-solid fa-print"></i> Cetak Lagi
        </button>
        <a href="{{ route('transactions.index') }}" class="btn btn-new">
            <i class="fa-solid fa-cart-plus"></i> Kasir Baru
        </a>
        <a href="{{ route('transactions.history') }}" class="btn btn-history">
            <i class="fa-solid fa-clock-rotate-left"></i> Riwayat
        </a>
    </div>

    <div class="receipt-container">
        
        <div class="header">
            <h2 class="store-name">TOKO MAJU JAYA</h2>
            <div class="store-address">
                Jl. Margonda Raya No. 100<br>
                Depok, Jawa Barat
            </div>
        </div>

        <div class="transaction-info">
            <div><strong>Tgl:</strong> {{ date('d/m/Y H:i', strtotime($transaction->created_at)) }}</div>
            <div><strong>No :</strong> {{ $transaction->invoice_code }}</div>
            <div><strong>Ksr:</strong> Admin</div> </div>

        <div class="item-list">
            @foreach($transaction->details as $detail)
            <div class="item">
                <span class="item-name">{{ $detail->product->name }}</span>
                <div class="item-details">
                    <span>{{ $detail->qty }} x {{ number_format($detail->product->price, 0, ',', '.') }}</span>
                    <span>{{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                </div>
            </div>
            @endforeach
        </div>

        <div class="total-section">
            <div class="total-row grand-total">
                <span>TOTAL</span>
                <span>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</span>
            </div>
            </div>
        
        <div class="footer">
            <p>*** TERIMA KASIH ***</p>
            <p>Barang yang sudah dibeli<br>tidak dapat ditukar/dikembalikan</p>
        </div>

    </div>

</body>
</html>