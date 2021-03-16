import { Controller } from 'stimulus';

export default class extends Controller {
    connect() {
        console.log(this.element.innerHTML);
    }
}
