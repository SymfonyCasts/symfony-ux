import { Controller } from 'stimulus';

export default class extends Controller {
    connect() {
        console.log('I want transitions!');
    }

    toggle(event) {
        console.log(event);
    }
}
