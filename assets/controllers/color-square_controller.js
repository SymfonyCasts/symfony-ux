import { Controller } from 'stimulus';

export default class extends Controller {
    static targets = ['colorSquare', 'select']

    selectColor(event) {
        this.colorSquareTargets.forEach((element) => {
            element.classList.remove('selected');
        });

        event.currentTarget.classList.add('selected');
        this.selectTarget.value = event.currentTarget.dataset.colorId;
    }
}
