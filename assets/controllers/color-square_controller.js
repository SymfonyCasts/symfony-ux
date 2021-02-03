import { Controller } from 'stimulus';

export default class extends Controller {
    selectedColorId = null;

    static targets = ['colorSquare', 'select']

    connect() {
        this.selectTarget.classList.add('d-none');
    }

    selectColor(event) {
        const newColorId = event.currentTarget.dataset.colorId;

        if (newColorId === this.selectedColorId) {
            event.currentTarget.classList.remove('selected');

            this.selectedColorId = null;
            this.selectTarget.value = '';

            return;
        }

        this.selectedColorId = newColorId;

        this.colorSquareTargets.forEach((element) => {
            element.classList.remove('selected');
        });

        event.currentTarget.classList.add('selected');
        this.selectTarget.value = this.selectedColorId;
    }
}
