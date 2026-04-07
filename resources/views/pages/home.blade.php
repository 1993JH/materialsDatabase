<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Wall-E - {{ config('app.name', 'Laravel') }}</title>

        <style>
            body {
                margin: 0;
                font-family: Arial, sans-serif;
                background: #f8fafc;
                color: #0f172a;
            }

            .container {
                max-width: 1100px;
                margin: 0 auto;
                padding: 2rem 1rem;
            }

            .header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 1.5rem;
            }

            .nav {
                display: flex;
                gap: 0.75rem;
            }

            .link-btn {
                display: inline-block;
                padding: 0.4rem 0.8rem;
                border: 1px solid #cbd5e1;
                border-radius: 0.375rem;
                background: #fff;
                text-decoration: none;
                color: inherit;
                font-size: 0.9rem;
            }

            .table-wrap {
                overflow-x: auto;
                border: 1px solid #e2e8f0;
                border-radius: 0.75rem;
                background: #fff;
            }

            .cards {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
                gap: 1rem;
                margin-top: 1rem;
            }

            .card {
                border: 1px solid #e2e8f0;
                border-radius: 0.75rem;
                background: #fff;
                padding: 1rem;
                box-shadow: 0 1px 2px rgba(15, 23, 42, 0.05);
            }

            .card-title {
                margin: 0 0 0.75rem;
                font-size: 1rem;
                line-height: 1.4;
            }

            .tag-list {
                display: flex;
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .tag {
                display: inline-flex;
                align-items: center;
                border: 1px solid #cbd5e1;
                background: #f8fafc;
                border-radius: 999px;
                padding: 0.25rem 0.65rem;
                font-size: 0.8rem;
                color: #334155;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                font-size: 0.9rem;
            }

            th,
            td {
                padding: 0.75rem;
                border-bottom: 1px solid #f1f5f9;
                text-align: left;
            }

            thead {
                background: #f1f5f9;
            }

            .empty {
                text-align: center;
                color: #64748b;
                padding: 1.25rem;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <header class="header">
                <h1>Wall-E</h1>

                <p>Total records: {{ $walls->count() }}</p>

                @if (Route::has('login'))
                    <nav class="nav">
                        @auth
                            <a href="{{ route('dashboard') }}" class="link-btn">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="link-btn">
                                Log in
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="link-btn">
                                    Register
                                </a>
                            @endif
                        @endauth
                    </nav>
                @endif
            </header>

            @if ($walls->isNotEmpty())
                <section class="cards">
                    @foreach ($walls as $wall)
                        <article class="card">
                            <h2 class="card-title">{{ $wall->Assembly_Description }}</h2>

                            <div class="tag-list">
                                <span class="tag">Climate Zone: {{ $wall->Climate_Zone }}</span>
                                <span class="tag">Wall Type: {{ $wall->Wall_Type }}</span>
                                <span class="tag">R/U Value: {{ $wall->R_Value_U_Value }}</span>
                            </div>
                        </article>
                    @endforeach
                </section>
            @else
                <section class="table-wrap">
                    <p class="empty">No wall records found.</p>
                </section>
            @endif
        </div>
    </body>
</html>
