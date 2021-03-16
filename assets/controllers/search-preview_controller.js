import { Controller } from 'stimulus';

export default class extends Controller {
    static values = {
        url: String,
    }

    onSearchInput(event) {
        const params = new URLSearchParams({
            q: event.currentTarget.value,
            preview: 1,
        });
        fetch(`${this.urlValue}?${params.toString()}`);
    }
}
