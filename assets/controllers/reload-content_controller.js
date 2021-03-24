import { Controller } from 'stimulus';

export default class extends Controller {
    static targets = ['content'];
    static values = {
        url: String,
    }

    async refreshContent(event) {
        const target = this.hasContentTarget ? this.contentTarget : this.element;

        target.style.opacity = .5;
        const response = await fetch(this.urlValue);
        target.innerHTML = await response.text();
        target.style.opacity = 1;
    }
}
