import { Controller } from 'stimulus';

export default class extends Controller {
    selectColor(event) {
        event.currentTarget.classList.add('selected');
    }
}
