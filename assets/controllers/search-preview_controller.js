import { Controller } from 'stimulus';

export default class extends Controller {
    static values = {
        url: String,
    }

    onSearchKeyUp(event) {
        console.log(this.urlValue);
    }
}
