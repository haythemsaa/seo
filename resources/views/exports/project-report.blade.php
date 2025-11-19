<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport SEO - {{ $project->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.6;
            color: #333;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 12px;
            opacity: 0.9;
        }

        .project-info {
            background: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
        }

        .project-info table {
            width: 100%;
        }

        .project-info td {
            padding: 5px;
        }

        .project-info td:first-child {
            font-weight: bold;
            width: 150px;
        }

        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }

        .stat-card {
            display: table-cell;
            width: 25%;
            padding: 15px;
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            text-align: center;
        }

        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 10px;
            color: #6c757d;
            text-transform: uppercase;
        }

        h2 {
            color: #667eea;
            font-size: 18px;
            margin: 30px 0 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        table thead {
            background: #667eea;
            color: white;
        }

        table th {
            padding: 10px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
        }

        table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e9ecef;
            font-size: 10px;
        }

        table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }

        .badge-success {
            background: #28a745;
            color: white;
        }

        .badge-danger {
            background: #dc3545;
            color: white;
        }

        .badge-warning {
            background: #ffc107;
            color: #333;
        }

        .badge-info {
            background: #17a2b8;
            color: white;
        }

        .badge-secondary {
            background: #6c757d;
            color: white;
        }

        .position-badge {
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 3px;
        }

        .position-top-3 {
            background: #28a745;
            color: white;
        }

        .position-top-10 {
            background: #17a2b8;
            color: white;
        }

        .position-top-20 {
            background: #ffc107;
            color: #333;
        }

        .position-low {
            background: #dc3545;
            color: white;
        }

        .trend-up {
            color: #28a745;
            font-weight: bold;
        }

        .trend-down {
            color: #dc3545;
            font-weight: bold;
        }

        .trend-stable {
            color: #6c757d;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e9ecef;
            text-align: center;
            font-size: 9px;
            color: #6c757d;
        }

        .page-break {
            page-break-after: always;
        }

        .summary-box {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            margin-bottom: 20px;
        }

        .summary-box h3 {
            color: #1976d2;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .summary-box ul {
            margin-left: 20px;
        }

        .summary-box li {
            margin-bottom: 5px;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>Rapport SEO Complet</h1>
        <p>{{ $project->name }} - {{ $project->url }}</p>
        <p>Généré le {{ $generatedAt }}</p>
    </div>

    <!-- Project Information -->
    <div class="project-info">
        <table>
            <tr>
                <td>Nom du Projet:</td>
                <td>{{ $project->name }}</td>
            </tr>
            <tr>
                <td>URL:</td>
                <td>{{ $project->url }}</td>
            </tr>
            <tr>
                <td>Description:</td>
                <td>{{ $project->description ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>Pays Cible:</td>
                <td>{{ $project->target_country }}</td>
            </tr>
            <tr>
                <td>Langue Cible:</td>
                <td>{{ $project->target_language }}</td>
            </tr>
            <tr>
                <td>Statut:</td>
                <td><span class="badge badge-{{ $project->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($project->status) }}</span></td>
            </tr>
        </table>
    </div>

    <!-- Statistics Overview -->
    <h2>Vue d'Ensemble des Statistiques</h2>
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value">{{ $statistics['total_keywords'] }}</div>
            <div class="stat-label">Mots-clés Suivis</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $statistics['top_ten_keywords'] }}</div>
            <div class="stat-label">Top 10 Positions</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $statistics['total_backlinks'] }}</div>
            <div class="stat-label">Backlinks Total</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $statistics['active_backlinks'] }}</div>
            <div class="stat-label">Backlinks Actifs</div>
        </div>
    </div>

    <!-- Summary Box -->
    <div class="summary-box">
        <h3>Résumé des Performances</h3>
        <ul>
            <li><strong>Mots-clés en progression:</strong> {{ $statistics['improved_keywords'] }} ({{ $statistics['total_keywords'] > 0 ? round(($statistics['improved_keywords'] / $statistics['total_keywords']) * 100, 1) : 0 }}%)</li>
            <li><strong>Mots-clés en régression:</strong> {{ $statistics['declined_keywords'] }} ({{ $statistics['total_keywords'] > 0 ? round(($statistics['declined_keywords'] / $statistics['total_keywords']) * 100, 1) : 0 }}%)</li>
            <li><strong>Mots-clés stables:</strong> {{ $statistics['stable_keywords'] }}</li>
            <li><strong>Domain Authority Moyenne:</strong> {{ $statistics['avg_domain_authority'] }}/100</li>
            <li><strong>Spam Score Moyen:</strong> {{ $statistics['avg_spam_score'] }}%</li>
            <li><strong>Backlinks DoFollow:</strong> {{ $statistics['dofollow_backlinks'] }} ({{ $statistics['active_backlinks'] > 0 ? round(($statistics['dofollow_backlinks'] / $statistics['active_backlinks']) * 100, 1) : 0 }}%)</li>
        </ul>
    </div>

    <!-- Keywords Table -->
    <h2>Détail des Mots-clés (Top 20)</h2>
    <table>
        <thead>
            <tr>
                <th>Mot-clé</th>
                <th>Position</th>
                <th>Évolution</th>
                <th>Meilleure</th>
                <th>Volume</th>
                <th>Dernière Vérif.</th>
            </tr>
        </thead>
        <tbody>
            @foreach($keywords->take(20) as $keyword)
                @php
                    $evolution = $keyword->previous_position ? ($keyword->previous_position - $keyword->current_position) : 0;
                    $position = $keyword->current_position ?? 999;
                    $positionClass = $position <= 3 ? 'position-top-3' : ($position <= 10 ? 'position-top-10' : ($position <= 20 ? 'position-top-20' : 'position-low'));
                @endphp
                <tr>
                    <td><strong>{{ $keyword->keyword }}</strong></td>
                    <td><span class="position-badge {{ $positionClass }}">{{ $keyword->current_position ?? 'N/A' }}</span></td>
                    <td>
                        @if($evolution > 0)
                            <span class="trend-up">↑ +{{ $evolution }}</span>
                        @elseif($evolution < 0)
                            <span class="trend-down">↓ {{ $evolution }}</span>
                        @else
                            <span class="trend-stable">→ 0</span>
                        @endif
                    </td>
                    <td>{{ $keyword->best_position ?? 'N/A' }}</td>
                    <td>{{ number_format($keyword->search_volume ?? 0, 0, ',', ' ') }}</td>
                    <td>{{ $keyword->last_check ? $keyword->last_check->format('d/m/Y') : 'Jamais' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="page-break"></div>

    <!-- Backlinks Table -->
    <h2>Backlinks de Qualité (Top 20)</h2>
    <table>
        <thead>
            <tr>
                <th>URL Source</th>
                <th>Ancre</th>
                <th>Type</th>
                <th>DA</th>
                <th>PA</th>
                <th>Spam</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($backlinks->sortByDesc('domain_authority')->take(20) as $backlink)
                @php
                    $spamLevel = $backlink->spam_score < 20 ? 'success' : ($backlink->spam_score < 50 ? 'warning' : 'danger');
                @endphp
                <tr>
                    <td><small>{{ substr($backlink->source_url, 0, 50) }}{{ strlen($backlink->source_url) > 50 ? '...' : '' }}</small></td>
                    <td>{{ $backlink->anchor_text }}</td>
                    <td><span class="badge badge-{{ $backlink->rel_attribute === 'dofollow' ? 'success' : 'secondary' }}">{{ $backlink->rel_attribute }}</span></td>
                    <td><strong>{{ $backlink->domain_authority ?? 'N/A' }}</strong></td>
                    <td>{{ $backlink->page_authority ?? 'N/A' }}</td>
                    <td><span class="badge badge-{{ $spamLevel }}">{{ $backlink->spam_score ?? 'N/A' }}</span></td>
                    <td><span class="badge badge-{{ $backlink->status === 'active' ? 'info' : 'danger' }}">{{ ucfirst($backlink->status) }}</span></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>© {{ date('Y') }} SEO Master Pro - Rapport généré automatiquement</p>
        <p>Ce rapport est confidentiel et destiné uniquement à un usage interne</p>
        <p>Page {{ $generatedAt }}</p>
    </div>
</body>
</html>
