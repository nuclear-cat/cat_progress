export class SidebarSwitcher {
    private sidebarElements;

    constructor(sidebarElements: Sidebar[]) {
        this.sidebarElements = sidebarElements;
    }

    public show(sidebarElement: Sidebar): void
    {
        this.hideAll();

        sidebarElement.show();
    }

    public hideAll(): void
    {
        this.sidebarElements.forEach(sidebarElement => {
            sidebarElement.hide();
        });
    }
}

export class Sidebar {
    private sidebarElement;

    constructor(element: HTMLElement) {
        this.sidebarElement = element;
    }

    public show(): void {
        this.sidebarElement.style.display = 'block';
    }

    public hide(): void {
        this.sidebarElement.style.display = 'none';
    }
}
