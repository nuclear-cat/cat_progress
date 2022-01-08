import { Controller } from 'stimulus';

export default class extends Controller {
    selectColor(event) {
        this.element.querySelectorAll('.js-select-color').forEach(element => {
            element.classList.remove('btn-outline-secondary');
        });

        event.currentTarget.classList.add('btn-outline-secondary');

        (<HTMLInputElement>this.element.querySelector('.js-color-select')).value = event.currentTarget.dataset.value;
    }
}
