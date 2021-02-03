import { Controller } from 'stimulus';
import { useClickOutside, useDebounce } from 'stimulus-use';

export default class extends Controller {
    static values = {
        url: String,
    }

    static targets = ['results'];
    static debounces = ['search'];

    connect() {
        useClickOutside(this);
        useDebounce(this);
    }

    onSearchKeyUp(event) {
        this.search(event.currentTarget.value);
    }

    async search(term) {
        const params = new URLSearchParams({
            q: term,
            preview: 1,
        });
        const response = await fetch(`${this.urlValue}?${params.toString()}`);

        this.resultsTarget.innerHTML = await response.text();
    }

    clickOutside(event) {
        this.resultsTarget.innerHTML = '';
    }
}
