/**
 * Revenue analytics — expects window.__WP_CRM_REVENUE_CHARTS
 */
'use strict';

(function () {
  const data = window.__WP_CRM_REVENUE_CHARTS;
  if (!data || typeof ApexCharts === 'undefined' || typeof config === 'undefined') {
    return;
  }

  const labelColor = config.colors.textMuted;
  const borderColor = config.colors.borderColor;
  const fontFamily = config.fontFamily;

  const yearEl = document.querySelector('#revenueYearChart');
  if (yearEl && data.yearLabels && data.yearSeries) {
    new ApexCharts(yearEl, {
      chart: {
        type: 'area',
        height: 320,
        toolbar: { show: false },
        animations: { enabled: true, easing: 'easeinout', speed: 600 }
      },
      series: [{ name: 'Revenue', data: data.yearSeries }],
      colors: [config.colors.primary],
      dataLabels: { enabled: false },
      stroke: { curve: 'smooth', width: 3 },
      fill: {
        type: 'gradient',
        gradient: {
          shadeIntensity: 0.4,
          opacityFrom: 0.35,
          opacityTo: 0.05,
          stops: [0, 90, 100]
        }
      },
      xaxis: {
        categories: data.yearLabels,
        labels: { style: { colors: labelColor, fontFamily } },
        axisBorder: { show: false },
        axisTicks: { show: false }
      },
      yaxis: {
        labels: {
          style: { colors: labelColor, fontFamily },
          formatter: function (val) {
            return Math.round(val);
          }
        }
      },
      grid: { borderColor, strokeDashArray: 4 },
      tooltip: {
        y: {
          formatter: function (val) {
            return (data.currencySymbol || '₹') + Math.round(val).toLocaleString();
          }
        }
      }
    }).render();
  }

  const periodEl = document.querySelector('#revenuePeriodChart');
  if (periodEl && data.periodLabels && data.periodSeries && data.periodLabels.length > 0) {
    new ApexCharts(periodEl, {
      chart: {
        type: 'line',
        height: 300,
        toolbar: { show: false },
        animations: { enabled: true, easing: 'easeinout', speed: 550 }
      },
      series: [{ name: 'Revenue', data: data.periodSeries }],
      colors: [config.colors.info],
      stroke: { curve: 'smooth', width: 3 },
      dataLabels: { enabled: false },
      markers: { size: 4, strokeWidth: 2, strokeColors: config.colors.info },
      xaxis: {
        categories: data.periodLabels,
        labels: { style: { colors: labelColor, fontFamily }, rotate: -35 }
      },
      yaxis: {
        labels: {
          style: { colors: labelColor, fontFamily },
          formatter: function (val) {
            return Math.round(val);
          }
        }
      },
      grid: { borderColor, strokeDashArray: 4 },
      tooltip: {
        y: {
          formatter: function (val) {
            return (data.currencySymbol || '₹') + Math.round(val).toLocaleString();
          }
        }
      }
    }).render();
  }

  const planEl = document.querySelector('#revenuePlanChart');
  if (planEl && data.planLabels && data.planSeries && data.planLabels.length > 0) {
    new ApexCharts(planEl, {
      chart: {
        type: 'donut',
        height: 300,
        animations: { enabled: true, easing: 'easeinout', speed: 550 }
      },
      series: data.planSeries,
      labels: data.planLabels,
      colors: [
        config.colors.primary,
        config.colors.success,
        config.colors.warning,
        config.colors.info,
        config.colors.danger
      ],
      legend: { position: 'bottom', fontFamily },
      dataLabels: { enabled: true },
      stroke: { width: 2, colors: [config.colors.cardColor] },
      plotOptions: {
        pie: {
          donut: {
            size: '68%',
            labels: {
              show: true,
              total: {
                show: true,
                label: 'Total',
                formatter: function () {
                  const t = data.planSeries.reduce(function (a, b) {
                    return a + b;
                  }, 0);
                  return (data.currencySymbol || '₹') + Math.round(t).toLocaleString();
                }
              }
            }
          }
        }
      },
      tooltip: {
        y: {
          formatter: function (val) {
            return (data.currencySymbol || '₹') + Math.round(val).toLocaleString();
          }
        }
      }
    }).render();
  }
})();
