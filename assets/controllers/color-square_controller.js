import { Controller } from 'stimulus';

export default class extends Controller {
    selectedColorId = null;

    static targets = ['colorSquare', 'select']
    static values = {
        colorId: Number
    }

    connect() {
        this.selectTarget.classList.add('d-none');

        console.log(this.colorIdValue);
    }

    selectColor(event) {
        this.setSelectedColor(event.currentTarget.dataset.colorId)
    }

    setSelectedColor(newColorId) {
        if (newColorId === this.selectedColorId) {
            this.findSelectedColorSquare().classList.remove('selected');

            this.selectedColorId = null;
            this.selectTarget.value = '';

            return;
        }

        this.selectedColorId = newColorId;

        this.colorSquareTargets.forEach((element) => {
            element.classList.remove('selected');
        });

        this.findSelectedColorSquare().classList.add('selected');
        this.selectTarget.value = this.selectedColorId;
    }

    /**
     * @return {Element|null}
     */
    findSelectedColorSquare() {
        return this.colorSquareTargets.find((element) => element.dataset.colorId === this.selectedColorId);
    }
}
