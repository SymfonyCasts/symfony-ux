import { Controller } from 'stimulus';

export default class extends Controller {
    static values = {
        url: String,
    }

    onSearchInput(event) {
        console.log(this.urlValue);
    }
}
