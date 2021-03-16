import { Controller } from 'stimulus';

export default class extends Controller {
    onSearchInput(event) {
        console.log(event);
    }
}
