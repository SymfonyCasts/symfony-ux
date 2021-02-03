import { Controller } from 'stimulus';

export default class extends Controller {
    onSearchKeyUp(event) {
        console.log(event);
    }
}
