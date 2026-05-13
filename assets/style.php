<style>
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        background-color: #ccc;
    }

    .container {
        width: 100%;
        height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    h1 {
        text-align: center;
    }

    .table-container {
        width: 90%;
        margin: 2rem auto 0;
    }

    .table-wrapper {
        background-color: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
    }

    .table-header {
        padding: 14px 20px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        gap: 8px;
        background-color: #fff;
    }

    .table-header span {
        font-size: 15px;
        font-weight: 600;
        color: #111;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    thead tr {
        background-color: #f9fafb;
    }

    th {
        padding: 10px 16px;
        text-align: left;
        font-size: 11px;
        font-weight: 600;
        color: #6b7280;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        border-bottom: 1px solid #e5e7eb;
        background-color: #f9fafb;
    }

    td {
        padding: 12px 16px;
        border-bottom: 1px solid #f0f0f0;
        font-size: 14px;
        color: #374151;
    }

    tbody tr:last-child td {
        border-bottom: none;
    }

    tbody tr:hover td {
        background-color: #f9fafb;
    }

    /* Badge fungsi semantik */
    .badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 500;
    }

    .badge-subjek {
        background: #E6F1FB;
        color: #0C447C;
    }

    .badge-predikat {
        background: #EAF3DE;
        color: #27500A;
    }

    .badge-objek {
        background: #FAEEDA;
        color: #633806;
    }

    .badge-keterangan {
        background: #FAECE7;
        color: #712B13;
    }

    .badge-default {
        background: #f3f4f6;
        color: #6b7280;
    }

    .no-result td {
        text-align: center;
        color: #9ca3af;
        padding: 32px;
        font-size: 14px;
    }

    .table-legend {
        padding: 10px 16px;
        border-top: 1px solid #f0f0f0;
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
    }

    .legend-item {
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
        color: #6b7280;
    }

    .legend-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }

    .no-result {
        text-align: center;
        color: #747474c8;
        background-color: #545454e2;
    }

    /* .subjek {
        background-color: #d9edf7;
    }

    .predikat {
        background-color: #dff0d8;
    }

    .objek {
        background-color: #fcf8e3;
    }

    .keterangan {
        background-color: #f2dede;
    }

    .not-found {
        color: red;
    } */

    .form-container {
        width: 90%;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        background-color: transparent;
        /* hapus background merah */
        height: auto;
        /* hapus height: 250px */
    }

    .form-card {
        width: 100%;
        padding: 1.75rem 1.5rem;
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        gap: 1rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .form-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 4px;
    }

    .form-header h2 {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
    }

    .form-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .icon-blue {
        background-color: #e6f1fb;
        color: #185fa5;
    }

    .icon-green {
        background-color: #eaf3de;
        color: #3b6d11;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .form-group label {
        font-size: 13px;
        font-weight: 500;
        color: #555;
    }

    .form-group input[type="text"],
    .form-group select {
        width: 100%;
        height: 42px;
        padding: 0 12px;
        border: 1.5px solid #ccc;
        border-radius: 8px;
        font-size: 14px;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-group input[type="text"]:focus,
    .form-group select:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.15);
    }

    .btn-analisis,
    .btn-tambah {
        width: 100%;
        height: 40px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s, transform 0.1s;
    }

    .btn-analisis {
        background-color: #007bff;
        color: white;
    }

    .btn-analisis:hover {
        background-color: #0056b3;
        transform: translateY(-1px);
    }

    .btn-tambah {
        background-color: #28a745;
        color: white;
    }

    .btn-tambah:hover {
        background-color: #1e7e34;
        transform: translateY(-1px);
    }

    .alert-success {
        padding: 10px 16px;
        border-radius: 8px;
        font-size: 13px;
        color: #155724;
        background-color: #d4edda;
        border: 1px solid #c3e6cb;
    }

    .alert-warning {
        padding: 10px 16px;
        border-radius: 8px;
        font-size: 13px;
        color: #856404;
        background-color: #fff3cd;
        border: 1px solid #ffeeba;
    }

    .container-card {
        background: #f9f9f8;
        border-radius: 12px;
        padding: 1.5rem;
        margin-top: 15px;
    }

    .card {
        background: #fff;
        border: 0.5px solid #e0ddd5;
        border-radius: 12px;
        padding: 1.5rem 1.75rem;
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />