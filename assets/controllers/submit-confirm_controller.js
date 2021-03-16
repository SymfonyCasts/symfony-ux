import { Controller } from 'stimulus';

export default class extends Controller {
    onSubmit(event) {
        event.preventDefault();
        console.log(event);
    }
}
