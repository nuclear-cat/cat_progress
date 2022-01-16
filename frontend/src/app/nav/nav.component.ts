import {AfterViewInit, Component, ViewChild} from '@angular/core';
import {BreakpointObserver, Breakpoints} from '@angular/cdk/layout';
import {Observable} from 'rxjs';
import {map, shareReplay} from 'rxjs/operators';
import {AuthService} from "../services/auth.service";
import {Router} from "@angular/router";
import {NavService} from "../services/nav-service";

@Component({
  selector: 'app-nav',
  templateUrl: './nav.component.html',
  styleUrls: ['./nav.component.scss']
})
export class NavComponent implements AfterViewInit {
  public isHandset$: Observable<boolean> = this.breakpointObserver.observe(Breakpoints.Handset)
      .pipe(
          map(result => result.matches),
          shareReplay()
      );

  @ViewChild('item') public item: any;
  public isLoading: boolean = true;
  public title = '';

  constructor(
      private breakpointObserver: BreakpointObserver,
      public authService: AuthService,
      private router: Router,
      public navService: NavService,
  ) {
  }

  public logout(): void {
    this.authService.logout();

    this.router.navigate(['/login']);
  }

  public ngAfterViewInit(): void {
    if (this.item) {
      this.isLoading = false;
    }
  }

  public pageAdded($event: any) {
    this.title = $event.title;
  }
}
