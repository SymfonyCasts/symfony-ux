import { Controller } from 'stimulus';

export default class extends Controller {
    connect() {
        console.log('I want transitions!');
    }

    toggleState(event) {
        console.log(event);
    }
}
