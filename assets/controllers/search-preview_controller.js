import { Controller } from 'stimulus';

export default class extends Controller {
    static values = {
        url: String,
    }

    async onSearchInput(event) {
        const params = new URLSearchParams({
            q: event.currentTarget.value,
            preview: 1,
        });
        const response = await fetch(`${this.urlValue}?${params.toString()}`);

        console.log(await response.text());
    }
}
