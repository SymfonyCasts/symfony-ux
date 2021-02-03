import { Controller } from 'stimulus';

export default class extends Controller {
    connect() {
        this.element.addEventListener('chartjs:connect', (event) => {
            this.onChartConnect(event);
        });
    }

    onChartConnect(event) {
        console.log(event);
    }
}
