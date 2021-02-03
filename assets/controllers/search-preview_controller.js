import { Controller } from 'stimulus';
import { useClickOutside } from 'stimulus-use';

export default class extends Controller {
    static values = {
        url: String,
    }

    static targets = ['results'];

    connect() {
        useClickOutside(this);
    }

    async onSearchKeyUp(event) {
        const params = new URLSearchParams({
            q: event.currentTarget.value,
            preview: 1,
        });
        const response = await fetch(`${this.urlValue}?${params.toString()}`);

        this.resultsTarget.innerHTML = await response.text();
    }

    clickOutside(event) {
        this.resultsTarget.innerHTML = '';
    }
}
