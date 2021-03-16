import { Controller } from 'stimulus';

export default class extends Controller {
    onChartConnect(event) {
        this.chart = event.detail.chart;

        setTimeout(() => {
            this.setNewData();
        }, 5000)
    }

    setNewData() {
        this.chart.data.datasets[0].data[2] = 30;
        this.chart.update();
    }
}
