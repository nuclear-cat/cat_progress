import {
  AfterViewInit,
  ApplicationRef,
  Component,
  ComponentFactoryResolver,
  Injector,
  OnDestroy,
  ViewChild
} from '@angular/core';
import {CdkPortal, DomPortalOutlet} from "@angular/cdk/portal";

@Component({
  selector: 'app-nav-title',
  templateUrl: './nav-title.component.html',
  styleUrls: ['./nav-title.component.scss']
})
export class NavTitleComponent implements AfterViewInit, OnDestroy {

  @ViewChild(CdkPortal) private portal!: CdkPortal;
  private portalOutlet!: DomPortalOutlet;


  public constructor(
      private componentFactoryResolver: ComponentFactoryResolver,
      private applicationRef: ApplicationRef,
      private injector: Injector,
  ) {
  }

  public ngAfterViewInit(): void {
    this.portalOutlet = new DomPortalOutlet(
        document.getElementById('navTitle')!,
        this.componentFactoryResolver,
        this.applicationRef,
        this.injector,
    );

    this.portalOutlet.attach(this.portal);
  }

  public ngOnDestroy(): void {
    this.portalOutlet.detach();
  }
}
