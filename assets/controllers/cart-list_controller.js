import { Controller } from 'stimulus';

export default class extends Controller {
    removeItem(event) {
        console.log(event.currentTarget);
    }
}
