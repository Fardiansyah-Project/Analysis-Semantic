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

    .btn {
        display: flex;
        justify-content: center;
        width: 100%;
    }

    .btn-primary {
        width: 40%;
        padding: 12px 24px;
        background-color: #007bff;
        color: white;
        font-size: 1rem;
        font-weight: 600;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.2s ease, transform 0.1s ease;
        letter-spacing: 0.5px;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        transform: translateY(-1px);
        /* efek naik sedikit */
    }

    .btn-primary:active {
        transform: translateY(0);
        background-color: #004494;
    }


    .table-container {
        width: 100%;
        margin-top: 20px;
        display: flex;
        justify-content: center;
    }

    form {
        width: 80%;
        padding: 40px 30px;
        background-color: #f9f9f9;
        /* lebih bersih dari #bbbaba */
        border: 1px solid #ddd;
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        gap: 16px;
        align-items: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    form input[type="text"] {
        width: 85%;
        height: 50px;
        padding: 10px 16px;
        border: 1.5px solid #ccc;
        border-radius: 8px;
        font-size: 1rem;
        color: #333;
        background-color: #fff;
        outline: none;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        text-align: center;
    }

    form input[type="text"]:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.15);
    }

    form input[type="text"]::placeholder {
        color: #aaa;
        font-size: 0.95rem;
    }

    table {
        width: 80%;
        border-collapse: collapse;
        border: 1px solid #02615c;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.1);
    }

    th {
        padding: 12px;
        background-color: #02615c;
        color: white;
        text-align: center;
        font-weight: 600;
        font-size: 1.5rem;
    }

    td {
        padding: 12px;
        border: none;
        border-bottom: 1px solid #b8f8f5;
        text-align: center;
        transition: background-color 0.2s;
        font-size: 1.5rem;
    }

    tr:hover td {
        background-color: #e0f2f1;
    }

    tr:nth-child(even) td {
        background-color: #f5fffe;
    }

    .no-result {
        text-align: center;
        color: #747474c8;
        background-color: #545454e2;
    }

    .subjek {
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
    }
</style>