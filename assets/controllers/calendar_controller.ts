import {Controller} from 'stimulus';
import {Sidebar, SidebarSwitcher } from '../sidebar';

export default class extends Controller {
    container = document.getElementById('calendarContainer');
    habitSidebar = new Sidebar(document.getElementById('habitSidebar'));
    habitCompletionSidebar = new Sidebar(document.getElementById('habitCompletionSidebar'));

    sidebarSwitcher = new SidebarSwitcher([
        this.habitSidebar,
        this.habitCompletionSidebar,
    ]);

    connect() {
        this.element.querySelectorAll('.js-show-habit-sidebar').forEach(item => {
            item.addEventListener('click', event => {
                event.preventDefault();

                this.hideSidebars();
                this.showHabitSidebar();
            });
        });

        this.element.querySelectorAll('.js-show-habit-completion-sidebar').forEach(item => {
            item.addEventListener('click', event => {
                event.preventDefault();

                this.hideSidebars();
                this.showHabitCompletionSidebar();
            });
        });

        this.element.querySelectorAll('.js-hide-calendar-sidebar').forEach(item => {
            item.addEventListener('click', event => {
                event.preventDefault();
                this.hideSidebars();
            });
        });
    }

    showHabitSidebar(): void {
        this.container.classList.remove('col-12');
        this.container.classList.add('col-9');
        this.habitSidebar.show();
    }

    showHabitCompletionSidebar(): void {
        this.container.classList.remove('col-12');
        this.container.classList.add('col-9');
        this.habitCompletionSidebar.show();
    }

    hideSidebars(): void {
        this.container.classList.add('col-12');
        this.container.classList.remove('col-9');

        this.sidebarSwitcher.hideAll();
    }
}
