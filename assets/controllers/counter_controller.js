import { Controller } from 'stimulus';

export default class extends Controller {
    connect() {
        this.count = 0;

        const counterNumberElement = this.element
            .getElementsByClassName('counter-count')[0];

        this.element.addEventListener('click', () => {
            this.count++;
            counterNumberElement.innerText = this.count;
        });
    }
}
