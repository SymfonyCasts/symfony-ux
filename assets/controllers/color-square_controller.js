import { Controller } from 'stimulus';

export default class extends Controller {
    static targets = ['colorSquare']

    selectColor(event) {
        this.colorSquareTargets.forEach((element) => {
            element.classList.remove('selected');
        });

        event.currentTarget.classList.add('selected');
        console.log(event.currentTarget.dataset.colorId);
    }
}
