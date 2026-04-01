/**
 * WP-CRM dashboard charts — reads window.__WP_CRM_DASHBOARD from the Blade view.
 */
'use strict';

(function () {
  const payload = window.__WP_CRM_DASHBOARD;
  if (!payload || typeof ApexCharts === 'undefined' || typeof config === 'undefined') {
    return;
  }

  const labelColor = config.colors.textMuted;
  const borderColor = config.colors.borderColor;
  const fontFamily = config.fontFamily;
  const cardColor = config.colors.cardColor;
  const chartBgColor = '#F0F2F8';

  const weeklyOverviewChartEl = document.querySelector('#weeklyOverviewChart');
  if (weeklyOverviewChartEl) {
    const data = payload.weeklyBar || [40, 55, 45, 75, 55, 35, 70];
    const categories = payload.weeklyCategories || ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
    const maxBar = Math.max.apply(null, data.concat([1]));
    const threshold = maxBar * 0.82;

    new ApexCharts(weeklyOverviewChartEl, {
      chart: {
        type: 'bar',
        height: 220,
        offsetY: -6,
        offsetX: -12,
        parentHeightOffset: 0,
        toolbar: { show: false }
      },
      series: [{ name: payload.weeklySeriesName || 'Activity', data }],
      colors: [chartBgColor],
      plotOptions: {
        bar: {
          borderRadius: 8,
          columnWidth: '32%',
          endingShape: 'rounded',
          startingShape: 'rounded',
          colors: {
            ranges: [
              { from: threshold, to: maxBar + 100, color: config.colors.primary },
              { from: 0, to: threshold - 0.01, color: chartBgColor }
            ]
          }
        }
      },
      dataLabels: { enabled: false },
      legend: { show: false },
      grid: {
        strokeDashArray: 8,
        borderColor,
        padding: { bottom: -8 }
      },
      xaxis: {
        categories,
        axisTicks: { show: false },
        axisBorder: { show: false },
        labels: {
          show: true,
          style: { fontSize: '12px', fontFamily, colors: labelColor }
        }
      },
      yaxis: {
        min: 0,
        max: Math.max(100, maxBar + 8),
        tickAmount: 4,
        labels: {
          formatter: function (val) {
            return parseInt(val, 10);
          },
          style: { fontSize: '12px', fontFamily, colors: labelColor }
        }
      },
      states: { hover: { filter: { type: 'none' } }, active: { filter: { type: 'none' } } }
    }).render();
  }

  const totalProfitLineChartEl = document.querySelector('#totalProfitLineChart');
  if (totalProfitLineChartEl) {
    const lineData = payload.lineTrend || [12, 28, 15, 42, 22, 56];
    const lastIdx = Math.max(0, lineData.length - 1);
    const lineMax = Math.max.apply(null, lineData.concat([10]));

    new ApexCharts(totalProfitLineChartEl, {
      chart: {
        height: 88,
        type: 'line',
        parentHeightOffset: 0,
        toolbar: { show: false }
      },
      grid: {
        borderColor,
        strokeDashArray: 6,
        xaxis: { lines: { show: true } },
        yaxis: { lines: { show: false } },
        padding: { top: -12, left: -4, right: 8, bottom: -12 }
      },
      colors: [config.colors.primary],
      stroke: { width: 3, curve: 'smooth' },
      series: [{ data: lineData }],
      tooltip: { enabled: false },
      xaxis: { labels: { show: false }, axisTicks: { show: false }, axisBorder: { show: false } },
      yaxis: { labels: { show: false }, min: 0, max: lineMax + 8 },
      markers: {
        size: 5,
        strokeWidth: 2,
        strokeColors: 'transparent',
        colors: ['transparent'],
        discrete: [
          {
            seriesIndex: 0,
            dataPointIndex: lastIdx,
            fillColor: cardColor,
            strokeColor: config.colors.primary,
            size: 6,
            shape: 'circle'
          }
        ],
        hover: { size: 7 }
      }
    }).render();
  }

  const sessionsColumnChartEl = document.querySelector('#sessionsColumnChart');
  if (sessionsColumnChartEl) {
    const bars = payload.columnBars || [35, 65, 48, 52, 70];

    new ApexCharts(sessionsColumnChartEl, {
      chart: {
        height: 88,
        parentHeightOffset: 0,
        type: 'bar',
        toolbar: { show: false }
      },
      tooltip: { enabled: false },
      plotOptions: {
        bar: {
          barHeight: '100%',
          columnWidth: '22%',
          borderRadius: 5,
          colors: {
            ranges: [
              { from: 80, to: 100, color: config.colors.primary },
              { from: 50, to: 79, color: config.colors.info },
              { from: 0, to: 49, color: chartBgColor }
            ],
            backgroundBarColors: bars.map(function () {
              return chartBgColor;
            }),
            backgroundBarRadius: 4
          }
        }
      },
      grid: { show: false, padding: { top: -8, left: -8, bottom: -12 } },
      dataLabels: { enabled: false },
      legend: { show: false },
      xaxis: { labels: { show: false }, axisTicks: { show: false }, axisBorder: { show: false } },
      yaxis: { labels: { show: false } },
      series: [{ data: bars }]
    }).render();
  }
})();
