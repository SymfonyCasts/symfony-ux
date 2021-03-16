import { Controller } from 'stimulus';

export default class extends Controller {
    selectedColorId = null;

    static targets = ['colorSquare', 'select']

    connect() {
        this.selectTarget.classList.add('d-none');
    }

    selectColor(event) {
        const clickedColorId = event.currentTarget.dataset.colorId;
        this.selectedColorId = clickedColorId;

        this.colorSquareTargets.forEach((element) => {
            element.classList.remove('selected');
        });

        event.currentTarget.classList.add('selected');
        this.selectTarget.value = this.selectedColorId;
    }
}
