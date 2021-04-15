import { Controller } from 'stimulus';
import { addFadeTransition } from '../util/add-transition';

export default class extends Controller {
    static targets = ['results'];

    connect() {
        addFadeTransition(this, this.resultsTarget);
    }

    toggle(event) {
        if (event.detail.action === 'open') {
            this.enter();
        } else {
            this.leave();
        }
    }
}
