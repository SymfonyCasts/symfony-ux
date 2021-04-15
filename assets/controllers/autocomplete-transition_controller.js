import { Controller } from 'stimulus';
import { addFadeTransition } from '../util/add-transition';

export default class extends Controller {
    connect() {
        addFadeTransition(this, this.resultsTarget);
    }

    toggleState(event) {
        //console.log('toggling!', event);
    }
}
