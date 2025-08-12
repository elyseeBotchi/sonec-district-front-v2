$(document).ready(function() {
    //findStatistique();

    //const intervalId = setInterval(findStatistique, 20000);
    //intervalId
    /* function findStatistique() {
    fetch(`/panel/services/historique-controle/penalite/${Entity_uuid}`, {
        method: 'GET',
        headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
    })
        .then((r) => {
        if (!r.ok) throw new Error('Erreur lors de la récupération des statistiques.');
        return r.json();
        })
        .then((res) => {
        const rows = Array.isArray(res?.data) ? res.data : [];

        // Détruit proprement un éventuel DataTable existant
        if ($.fn.DataTable.isDataTable('#statsTable')) {
            $('#statsTable').DataTable().clear().destroy();
        }

        const dt = $('#statsTable').DataTable({
            data: rows,
            columns: [
            { data: 'day', defaultContent: '' },
            {
                data: null,
                render: (d, t, r) => `${r?.firstname ?? ''} ${r?.lastname ?? ''}`.trim(),
                defaultContent: '',
            },
            { data: 'numero_dimmatriculation', defaultContent: '' },
            {
                data: null,
                render: (d, t, r) =>
                `${r?.rubrique_name ?? ''} ${r?.rubrique_option_name ?? ''}`.trim(),
                defaultContent: '',
            },
            ],
            pageLength: 10,
            lengthMenu: [[5, 10, 20, 50, 100, -1], [5, 10, 20, 50, 100, 'Tous']],
            order: [[0, 'desc'], [1, 'asc']],
            language: { url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/fr-FR.json' },
            dom: 'ftip',
        });

        // ---- Construire les jours disponibles
        const daySet = new Set();
        for (const r of rows) if (r?.day) daySet.add(r.day);
        const days = Array.from(daySet).sort();

        const $dayFilter = $('#dayFilter').empty();
        $dayFilter.append('<option value="">Tous les jours</option>');
        for (const d of days) $dayFilter.append(`<option value="${d}">${d}</option>`);

        function setDay(day) {
            if (!day) {
            dt.column(0).search('').draw(); // tous
            } else {
            // Filtre exact sur la colonne 0 (jour)
            dt.column(0).search(`^${day}$`, true, false).draw();
            }
        }

        // Sélection par défaut : dernier jour ou "Tous"
        const initialDay = days.length ? days[days.length - 1] : '';
        $dayFilter.val(initialDay);
        setDay(initialDay);

        $dayFilter.on('change', function () {
            setDay(this.value);
        });
        })
        .catch((e) => console.error('Erreur lors de la récupération des statistiques :', e));
    }
    */

    (function () {
    const $period = $('#periodSelect');
    const $range = $('#rangePicker');
    const $from  = $('#fromDate');
    const $to    = $('#toDate');

    function pad(n) { return String(n).padStart(2, '0'); }
    function fmtDate(d) { return `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}`; }

    function todayRange() {
        const d = new Date();
        const s = fmtDate(d);
        return { from: s, to: s };
    }
    function weekRange(date = new Date()) {
        // Lundi → Dimanche
        const d = new Date(date.getFullYear(), date.getMonth(), date.getDate());
        const day = d.getDay(); // 0=dim…6=sam
        const diffToMonday = (day + 6) % 7; // 0 si lundi
        const monday = new Date(d); monday.setDate(d.getDate() - diffToMonday);
        const sunday = new Date(monday); sunday.setDate(monday.getDate() + 6);
        return { from: fmtDate(monday), to: fmtDate(sunday) };
        // Si tu veux “jusqu’à aujourd’hui” : mets to = fmtDate(new Date())
    }
    function monthRange(date = new Date()) {
        const y = date.getFullYear(), m = date.getMonth();
        const first = new Date(y, m, 1);
        const last  = new Date(y, m + 1, 0);
        return { from: fmtDate(first), to: fmtDate(last) };
    }

    function toggleLoading(on) { $('#statsLoading').toggle(!!on); }

   function loadStatsByRange(fromStr, toStr) {
        const base = `/panel/services/historique-controle/penalite/${Entity_uuid}`;
        const url  = `${base}/${encodeURIComponent(fromStr)}/${encodeURIComponent(toStr)}`;

        // Helpers
        const pad2 = (n) => String(n).padStart(2, '0');
        function parseLocalDateTime(str) {
            if (!str) return null;
            // Compat Safari: "YYYY-MM-DD HH:mm:ss" -> "YYYY-MM-DDTHH:mm:ss"
            return new Date(str.replace(' ', 'T'));
        }
        function formatDDMMYYYY_HHMMSS(dt) {
            if (!dt || Number.isNaN(dt.getTime())) return '';
            return `${pad2(dt.getDate())}-${pad2(dt.getMonth() + 1)}-${dt.getFullYear()} ` +
                `${pad2(dt.getHours())}:${pad2(dt.getMinutes())}:${pad2(dt.getSeconds())}`;
        }

        toggleLoading(true);
        return fetch(url, {
            method: 'GET',
            headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
        })
            .then((r) => {
            if (!r.ok) throw new Error('Erreur lors de la récupération des statistiques.');
            return r.json();
            })
            .then((res) => {
            const rows = Array.isArray(res?.data) ? res.data : [];

            if ($.fn.DataTable.isDataTable('#statsTable')) {
                $('#statsTable').DataTable().clear().destroy();
            }

            $('#statsTable').DataTable({
                data: rows,
                autoWidth: false,
                columns: [
                { // Date & heure en JJ-MM-AAAA HH:mm:ss
                    data: 'checked_at',
                    render: (data, type, row) => {
                    const dt = parseLocalDateTime(data || (row?.day ? `${row.day} 00:00:00` : ''));
                    if (type === 'display' || type === 'filter') return formatDDMMYYYY_HHMMSS(dt);
                    // Pour tri: timestamp (nombre)
                    return dt ? dt.getTime() : 0;
                    },
                    defaultContent: '',
                },
                { // Agent
                    data: null,
                    render: (d, t, r) => `${r?.firstname ?? ''} ${r?.lastname ?? ''}`.trim(),
                    defaultContent: '',
                },
                { data: 'numero_dimmatriculation', defaultContent: '' },
                {
                    data: null,
                    render: (d, t, r) =>
                    `${r?.rubrique_name ?? ''} ${r?.rubrique_option_name ?? ''}`.trim(),
                    defaultContent: '',
                },
                ],
                pageLength: 10,
                lengthMenu: [[5, 10, 20, 50, 100, -1], [5, 10, 20, 50, 100, 'Tous']],
                order: [[0, 'desc'], [1, 'asc']], // tri d'abord par date/heure
                language: { url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/fr-FR.json' },
                dom: 'ftip',
            });
            })
            .catch((e) => console.error('Erreur lors de la récupération des statistiques :', e))
            .finally(() => toggleLoading(false));
        }





    function applyPeriod(value) {
        // Affiche/cache la zone “plage”
        const isRange = value === 'range';
        $range.toggle(isRange);

        if (isRange) return; // attendre le clic sur “Appliquer”

        let range;
        if (value === 'today') range = todayRange();
        else if (value === 'week') range = weekRange();
        else if (value === 'month') range = monthRange();
        else range = todayRange(); // fallback

        loadStatsByRange(range.from, range.to);
    }

    // Init
    $(function () {
        // Par défaut: aujourd'hui
        const { from, to } = todayRange();
        // Pré-remplit la plage avec aujourd’hui
        $from.val(from);
        $to.val(to);

        // Premier chargement
        applyPeriod($period.val() || 'today');

        // Changement de période
        $period.on('change', function () {
        applyPeriod(this.value);
        });

        // Appliquer la plage custom
        $('#applyRange').on('click', function () {
        const f = $from.val();
        const t = $to.val();
        if (!f || !t) {
            alert('Veuillez choisir une date de début et une date de fin.');
            return;
        }
        if (new Date(f) > new Date(t)) {
            alert('La date de début doit être antérieure ou égale à la date de fin.');
            return;
        }
        loadStatsByRange(f, t);
        });
    });
    })();




});
