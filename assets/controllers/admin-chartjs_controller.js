import { Controller } from 'stimulus';

export default class extends Controller {
    onChartConnect(event) {
        console.log(event);
    }
}
