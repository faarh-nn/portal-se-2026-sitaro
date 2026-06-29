import './bootstrap';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import intersect from '@alpinejs/intersect';
import { Chart, DoughnutController, ArcElement, Tooltip, Legend } from 'chart.js';

// Register Chart.js components
Chart.register(DoughnutController, ArcElement, Tooltip, Legend);

// Global Chart.js defaults for consistent styling
Chart.defaults.font.family = "'Poppins', ui-sans-serif, system-ui, sans-serif";
Chart.defaults.font.size = 12;
Chart.defaults.color = '#64748b';

// Custom plugin for center text
const centerTextPlugin = {
    id: 'centerText',
    afterDatasetsDraw(chart) {
        const { ctx, chartArea } = chart;
        const centerX = (chartArea.left + chartArea.right) / 2;
        const centerY = (chartArea.top + chartArea.bottom) / 2;

        // Calculate total
        const total = chart.data.datasets[0].data.reduce((a, b) => a + b, 0);

        // Draw total number
        ctx.save();
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';

        // Total value
        ctx.font = "bold 28px 'Poppins', sans-serif";
        ctx.fillStyle = '#231f20';
        ctx.fillText(chart.formatNumber ? chart.formatNumber(total) : total.toLocaleString('id-ID'), centerX, centerY - 10);

        // Label "Total"
        ctx.font = "500 12px 'Poppins', sans-serif";
        ctx.fillStyle = '#64748b';
        ctx.fillText('Total', centerX, centerY + 16);

        ctx.restore();
    }
};

Chart.register(centerTextPlugin);

// Donut Chart Component using Chart.js
Alpine.data('donutChart', (chartData) => ({
    chart: null,
    chartData: chartData,
    activeSegment: null,

    init() {
        this.$nextTick(() => {
            this.createChart();
        });
    },

    createChart() {
        const canvas = this.$refs.chartCanvas;
        if (!canvas) return;

        const ctx = canvas.getContext('2d');

        // Attach formatNumber to chart instance for plugin access
        const chartInstance = this;

        this.chart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: this.chartData.labels,
                datasets: [{
                    data: this.chartData.values,
                    backgroundColor: this.chartData.colors,
                    borderColor: '#ffffff',
                    borderWidth: 3,
                    hoverBorderWidth: 4,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                cutout: '60%',
                plugins: {
                    legend: {
                        display: false // Hide default legend, we use custom one
                    },
                    tooltip: {
                        enabled: true,
                        backgroundColor: 'rgba(255, 255, 255, 0.98)',
                        titleColor: '#64748b',
                        bodyColor: '#231f20',
                        bodyFont: {
                            size: 14,
                            weight: '700'
                        },
                        titleFont: {
                            size: 11,
                            weight: '500'
                        },
                        padding: 12,
                        borderColor: 'rgba(124, 58, 237, 0.2)',
                        borderWidth: 1,
                        displayColors: true,
                        boxPadding: 6,
                        callbacks: {
                            title: (items) => items[0].label,
                            label: (context) => {
                                const value = context.parsed;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                const formattedValue = new Intl.NumberFormat('id-ID').format(value);
                                return `${formattedValue} (${percentage}%)`;
                            }
                        }
                    }
                },
                onHover: (event, elements) => {
                    if (elements.length > 0) {
                        this.activeSegment = elements[0].index;
                        canvas.style.cursor = 'pointer';
                    } else {
                        this.activeSegment = null;
                        canvas.style.cursor = 'default';
                    }
                },
                animation: {
                    animateRotate: true,
                    animateScale: true,
                    duration: 800,
                    easing: 'easeOutQuart'
                }
            }
        });

        // Attach formatNumber to chart for plugin
        this.chart.formatNumber = this.formatNumber;
    },

    getTotal() {
        return this.chartData.values.reduce((a, b) => a + b, 0);
    },

    getPercentage(index) {
        const total = this.getTotal();
        if (total === 0) return 0;
        return ((this.chartData.values[index] / total) * 100).toFixed(1);
    },

    formatNumber(num) {
        return new Intl.NumberFormat('id-ID').format(num ?? 0);
    },

    destroy() {
        if (this.chart) {
            this.chart.destroy();
        }
    }
}));

// Monitoring subnav scroll spy store
Alpine.store('monitoring', {
    activeSection: '',
    init() {
        const sections = ['leaderboard-pcl', 'leaderboard-pml', 'progress-pcl', 'progress-pml', 'peta-kecamatan', 'tabel-kecamatan'];
        const updateActiveSection = () => {
            const scrollPosition = window.scrollY + 200;

            for (let i = sections.length - 1; i >= 0; i--) {
                const section = document.getElementById(sections[i]);
                if (section && section.offsetTop <= scrollPosition) {
                    this.activeSection = sections[i];
                    break;
                }
            }
        };

        window.addEventListener('scroll', updateActiveSection);
        updateActiveSection();
    }
});

// Navbar scroll behavior
document.addEventListener('DOMContentLoaded', () => {
    const navbar = document.getElementById('navbar');

    if (navbar) {
        const handleScroll = () => {
            if (window.scrollY > 20) {
                navbar.classList.add('navbar--scrolled');
            } else {
                navbar.classList.remove('navbar--scrolled');
            }
        };

        window.addEventListener('scroll', handleScroll);
        handleScroll();
    }

    // Mobile menu toggle
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');

    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenuBtn.classList.toggle('is-active');
            mobileMenu.classList.toggle('is-active');
        });

        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!navbar.contains(e.target) && mobileMenu.classList.contains('is-active')) {
                mobileMenuBtn.classList.remove('is-active');
                mobileMenu.classList.remove('is-active');
            }
        });

        // Close menu when pressing Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && mobileMenu.classList.contains('is-active')) {
                mobileMenuBtn.classList.remove('is-active');
                mobileMenu.classList.remove('is-active');
            }
        });
    }
});

Alpine.start();