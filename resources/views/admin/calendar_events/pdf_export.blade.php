<!DOCTYPE html>
<html>

<head>
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10px;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            margin: 0;
        }

        .header p {
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Tanggal Cetak: {{ $date }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Acara</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Jenis</th>
                <th>Lokasi</th>
                <th>Target Audiens</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($calendarEvents as $event)
                <tr>
                    <td>{{ $event->title }}</td>
                    <td>{{ $event->start_date->format('d-m-Y') }} @if ($event->end_date)
                            s/d {{ $event->end_date->format('d-m-Y') }}
                        @endif
                    </td>
                    <td>
                        @if ($event->start_time)
                            {{ $event->start_time->format('H:i') }}
                            @endif @if ($event->end_time)
                                - {{ $event->end_time->format('H:i') }}
                            @endif
                    </td>
                    <td>{{ $event->event_type }}</td>
                    <td>{{ $event->location }}</td>
                    <td>{{ $event->target_roles }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
