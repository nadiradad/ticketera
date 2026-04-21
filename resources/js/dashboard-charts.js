import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

Chart.defaults.font.family =
    'Figtree, ui-sans-serif, system-ui, sans-serif';
Chart.defaults.color = '#64748b';

const indigo = '#4f46e5';
const indigoSoft = 'rgba(79, 70, 229, 0.12)';

function readPayload() {
    const el = document.getElementById('dashboard-charts-payload');

    if (!el?.textContent) {
        return null;
    }

    try {
        return JSON.parse(el.textContent);
    } catch {
        return null;
    }
}

function destroyIfExists(key) {
    if (window.__dashboardCharts?.[key]) {
        window.__dashboardCharts[key].destroy();
        delete window.__dashboardCharts[key];
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const data = readPayload();

    if (!data) {
        return;
    }

    window.__dashboardCharts = window.__dashboardCharts ?? {};

    const estadosEl = document.getElementById('chart-tickets-por-estado');
    const diasEl = document.getElementById('chart-tickets-por-dia');
    const tecnicosEl = document.getElementById('chart-tickets-por-tecnico');

    if (estadosEl && data.porEstado) {
        destroyIfExists('estados');
        window.__dashboardCharts.estados = new Chart(estadosEl, {
            type: 'doughnut',
            data: {
                labels: data.porEstado.map((d) => d.label),
                datasets: [
                    {
                        data: data.porEstado.map((d) => d.count),
                        backgroundColor: data.porEstado.map((d) => d.color),
                        borderWidth: 0,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 10, padding: 12 },
                    },
                },
                cutout: '62%',
            },
        });
    }

    if (diasEl && data.porDia) {
        destroyIfExists('dias');
        window.__dashboardCharts.dias = new Chart(diasEl, {
            type: 'line',
            data: {
                labels: data.porDia.labels,
                datasets: [
                    {
                        label: 'Tickets creados',
                        data: data.porDia.values,
                        borderColor: indigo,
                        backgroundColor: indigoSoft,
                        fill: true,
                        tension: 0.35,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        pointBackgroundColor: indigo,
                        borderWidth: 2,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0, stepSize: 1 },
                        grid: { color: 'rgba(148, 163, 184, 0.25)' },
                    },
                    x: {
                        grid: { display: false },
                        ticks: { maxRotation: 45, minRotation: 0 },
                    },
                },
                plugins: { legend: { display: false } },
            },
        });
    }

    if (tecnicosEl && data.porTecnico) {
        destroyIfExists('tecnicos');
        window.__dashboardCharts.tecnicos = new Chart(tecnicosEl, {
            type: 'bar',
            data: {
                labels: data.porTecnico.map((d) => d.label),
                datasets: [
                    {
                        label: 'Tickets',
                        data: data.porTecnico.map((d) => d.count),
                        backgroundColor: indigoSoft,
                        borderColor: indigo,
                        borderWidth: 1.5,
                        borderRadius: 6,
                    },
                ],
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: { precision: 0, stepSize: 1 },
                        grid: { color: 'rgba(148, 163, 184, 0.25)' },
                    },
                    y: { grid: { display: false } },
                },
                plugins: { legend: { display: false } },
            },
        });
    }
});
