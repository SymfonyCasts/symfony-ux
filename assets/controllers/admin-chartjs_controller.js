import { Controller } from 'stimulus';

export default class extends Controller {
    connect() {
        this.element.addEventListener('chartjs:connect', (event) => {
            this.onChartConnect(event);
        });
    }

    onChartConnect(event) {
        this.chart = event.detail.chart;

        setTimeout(() => {
            this.setNewData();
        }, 5000)
    }

    setNewData() {
        this.chart.data.datasets[0].data[2] = 3000;
        this.chart.update();
    }
}
