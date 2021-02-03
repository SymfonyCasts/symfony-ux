import { Controller } from 'stimulus';

export default class extends Controller {
    static targets = ['colorSquare']

    selectColor(event) {
        console.log(this.colorSquareTargets);

        event.currentTarget.classList.add('selected');
    }
}
