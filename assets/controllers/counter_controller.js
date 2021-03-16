import { Controller } from 'stimulus';

export default class extends Controller {
    count = 0;
    static targets = ['count'];

    increment() {
        this.count++;
        this.countTarget.innerText = this.count;
    }
}
