import { Controller } from 'stimulus';

export default class extends Controller {
    targets = ['colorSquare']

    selectColor(event) {
        console.log(this.colorSquareTargets);

        event.currentTarget.classList.add('selected');
    }
}
