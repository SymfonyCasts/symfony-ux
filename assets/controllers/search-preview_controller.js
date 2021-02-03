import { Controller } from 'stimulus';

export default class extends Controller {
    static values = {
        url: String,
    }

    static targets = ['results'];

    async onSearchKeyUp(event) {
        const params = new URLSearchParams({
            q: event.currentTarget.value,
            preview: 1,
        });
        const response = await fetch(`${this.urlValue}?${params.toString()}`);

        this.resultsTarget.innerHTML = await response.text();
    }
}
