import { Controller } from 'stimulus';
import { Modal } from 'bootstrap';

export default class extends Controller {
    openModal(event) {
        console.log(event);
    }
}
