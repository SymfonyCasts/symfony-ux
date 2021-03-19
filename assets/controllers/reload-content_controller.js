import { Controller } from 'stimulus';

export default class extends Controller {
    static targets = ['content'];
    static values = {
        url: String,
    }

    async refreshContent(event) {
        const response = await fetch(this.urlValue);
        this.contentTarget.innerHTML = await response.text();
    }
}
