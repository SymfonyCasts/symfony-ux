import { Controller } from 'stimulus';

export default class extends Controller {
    static values = {
        cartRefreshUrl: String,
    }

    async removeItem(event) {
        const response = await fetch(this.cartRefreshUrlValue);
        this.element.innerHTML = await response.text();
    }
}
