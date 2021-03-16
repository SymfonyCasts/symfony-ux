import { Controller } from 'stimulus';

export default class extends Controller {
    connect() {
        this.element.innerHTML = 'You have clicked me 0 times 😢';
    }
}
