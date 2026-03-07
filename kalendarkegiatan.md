<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalender Bulan Kegiatan BOK Puskesmas</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px 20px;
            min-height: 100vh;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
            padding: 30px;
            text-align: center;
            color: white;
        }
        
        .header h2 {
            font-size: 28px;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .header p {
            font-size: 14px;
            opacity: 0.95;
            font-weight: 300;
        }
        
        .table-wrapper {
            padding: 30px;
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border-radius: 8px;
            overflow: hidden;
        }
        
        th {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            padding: 16px 12px;
            text-align: center;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
        }
        
        th:first-child {
            text-align: left;
            padding-left: 20px;
            min-width: 250px;
        }
        
        td {
            padding: 18px 12px;
            text-align: center;
            font-size: 13px;
            border: 1px solid #e0e0e0;
            transition: all 0.3s ease;
        }
        
        td:first-child {
            text-align: left;
            padding-left: 20px;
            font-weight: 500;
            color: #2c3e50;
            background-color: #f8f9fa;
        }
        
        tr:hover td {
            background-color: #f0f7ff;
        }
        
        tr:hover td:first-child {
            background-color: #e3f2fd;
        }
        
        .tahap1 {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            font-weight: 600;
            font-size: 12px;
            padding: 8px;
            border-radius: 6px;
            border: 2px solid #28a745;
            box-shadow: 0 2px 4px rgba(40, 167, 69, 0.2);
        }
        
        .tahap2 {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            color: #856404;
            font-weight: 600;
            font-size: 12px;
            padding: 8px;
            border-radius: 6px;
            border: 2px solid #ffc107;
            box-shadow: 0 2px 4px rgba(255, 193, 7, 0.2);
        }
        
        .tahap3 {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
            font-weight: 600;
            font-size: 12px;
            padding: 8px;
            border-radius: 6px;
            border: 2px solid #dc3545;
            box-shadow: 0 2px 4px rgba(220, 53, 69, 0.2);
        }
        
        .keterangan {
            margin: 30px;
            padding: 25px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 10px;
            border-left: 5px solid #3498db;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }
        
        .keterangan strong {
            display: block;
            font-size: 16px;
            color: #2c3e50;
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            margin: 12px 0;
            font-size: 14px;
            color: #495057;
        }
        
        .legend-box {
            display: inline-block;
            width: 120px;
            padding: 8px 12px;
            border-radius: 6px;
            font-weight: 600;
            margin-right: 15px;
            text-align: center;
            font-size: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .legend-tahap1 {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border: 2px solid #28a745;
        }
        
        .legend-tahap2 {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            color: #856404;
            border: 2px solid #ffc107;
        }
        
        .legend-tahap3 {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
            border: 2px solid #dc3545;
        }
        
        .catatan {
            margin-top: 20px;
            padding: 15px;
            background-color: #fff9e6;
            border-left: 4px solid #ffc107;
            border-radius: 6px;
            font-size: 13px;
            color: #856404;
            font-style: italic;
        }
        
        .catatan::before {
            content: "📌 ";
            font-style: normal;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .container {
                box-shadow: none;
            }
        }
        
        @media (max-width: 768px) {
            .header h2 {
                font-size: 22px;
            }
            
            .table-wrapper {
                padding: 15px;
            }
            
            th, td {
                padding: 10px 6px;
                font-size: 11px;
            }
            
            th:first-child,
            td:first-child {
                min-width: 180px;
            }
            
            .legend-item {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .legend-box {
                margin-bottom: 8px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h2>KALENDER BULAN KEGIATAN BOK PUSKESMAS</h2>
        <p>Jadwal Pelaksanaan Kegiatan Program Bantuan Operasional Kesehatan</p>
    </div>
    
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Kegiatan</th>
                    <th>Jan</th>
                    <th>Feb</th>
                    <th>Mar</th>
                    <th>Apr</th>
                    <th>Mei</th>
                    <th>Jun</th>
                    <th>Jul</th>
                    <th>Agu</th>
                    <th>Sep</th>
                    <th>Okt</th>
                    <th>Nov</th>
                    <th>Des</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Pelacakan & Pengawasan Minum Obat ODGJ Berat</td>
                    <td></td>
                    <td><span class="tahap1">Tahap I</span></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><span class="tahap2">Tahap II</span></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><span class="tahap3">Tahap III</span></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Pelacakan & Pelaporan Kematian Ibu dan Bayi/Balita</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><span class="tahap2">Tahap II</span></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><span class="tahap3">Tahap III</span></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="keterangan">
        <strong>Keterangan Tahapan Kegiatan:</strong>
        
        <div class="legend-item">
            <span class="legend-box legend-tahap1">Tahap I</span>
            <span>Januari – April (1 kali kegiatan)</span>
        </div>
        
        <div class="legend-item">
            <span class="legend-box legend-tahap2">Tahap II</span>
            <span>Mei – Agustus (1 kali kegiatan)</span>
        </div>
        
        <div class="legend-item">
            <span class="legend-box legend-tahap3">Tahap III</span>
            <span>September – Desember (1 kali kegiatan)</span>
        </div>
        
        <div class="catatan">
            <strong>Catatan:</strong> Kegiatan dilaksanakan sebanyak 3 kali dalam 1 tahun, dengan pembagian merata pada setiap tahap pencairan BOK.
        </div>
    </div>
</div>
</body>
</html>