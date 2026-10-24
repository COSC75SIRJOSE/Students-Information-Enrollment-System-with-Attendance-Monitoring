<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') | SIEAM</title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f3f6fb;
            color: #243247;
        }

        .topbar {
            background: #163b65;
            color: white;
            padding: 18px 5%;
        }

        .topbar-inner {
            max-width: 1200px;
            margin: auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .brand {
            color: white;
            text-decoration: none;
            font-size: 20px;
            font-weight: bold;
        }

        .brand small {
            display: block;
            font-size: 11px;
            font-weight: normal;
            margin-top: 5px;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-left: 18px;
        }

        .container {
            max-width: 1200px;
            margin: 35px auto;
            padding: 0 20px;
        }

        .page-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 24px;
        }

        h1 { margin: 0 0 8px; font-size: 27px; }
        p { color: #65748b; }

        .card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 3px 15px rgba(30, 50, 80, .06);
            margin-bottom: 22px;
        }

        .button {
            display: inline-block;
            border: none;
            border-radius: 6px;
            padding: 11px 16px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
        }

        .primary { background: #1769aa; color: white; }
        .secondary { background: #e8edf4; color: #26364a; }
        .danger { background: #fee2e2; color: #b91c1c; }
        .small-button { padding: 7px 10px; font-size: 12px; }

        .table-wrap { overflow-x: auto; }

        table {
            width: 100%;
            border-collapse: collapse;
            white-space: nowrap;
        }

        th, td {
            padding: 14px 12px;
            text-align: left;
            border-bottom: 1px solid #e8edf3;
        }

        th {
            color: #52647a;
            font-size: 12px;
            text-transform: uppercase;
            background: #f8fafc;
        }

        .status {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 20px;
            font-size: 12px;
        }

        .status-active { background: #dcfce7; color: #166534; }
        .status-inactive { background: #fee2e2; color: #991b1b; }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
        }

        .field { margin-bottom: 2px; }
        .field label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: bold;
        }

        .field input, .field select, .field textarea {
            width: 100%;
            padding: 11px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font: inherit;
            background: white;
        }

        .field textarea { min-height: 90px; resize: vertical; }
        .full-width { grid-column: 1 / -1; }

        .alert-success {
            padding: 14px;
            background: #dcfce7;
            color: #166534;
            border-radius: 7px;
            margin-bottom: 20px;
        }

        .alert-error {
            padding: 14px;
            background: #fee2e2;
            color: #991b1b;
            border-radius: 7px;
            margin-bottom: 20px;
        }

        .actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .actions form { margin: 0; }
        .empty-state { padding: 30px; text-align: center; color: #65748b; }

        footer {
            text-align: center;
            padding: 25px;
            color: #748197;
            font-size: 12px;
        }

        @media (max-width: 650px) {
            .form-grid { grid-template-columns: 1fr; }
            .full-width { grid-column: auto; }
            nav a { margin-left: 0; margin-right: 12px; }
            h1 { font-size: 23px; }
            .card { padding: 16px; }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="topbar-inner">
            <a class="brand" href="{{ route('students.index') }}">
                SIEAM
                <small>Students Information, Enrollment & Attendance</small>
            </a>

            <nav>
                <a href="{{ route('students.index') }}">Students</a>
                <a href="{{ route('students.create') }}">Register Student</a>
                <a href="{{ route('attendance.index') }}">Attendance</a>
                <a href="{{ route('attendance.reports') }}">Attendance Reports</a>
            </nav>
        </div>
    </header>

    <main class="container">
        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-error">
                <strong>Please correct the following:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <footer>
        Students-Information-Enrollment-System-with-Attendance-Monitoring
    </footer>
</body>
</html>