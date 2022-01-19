import {Component, OnInit, ViewChild} from '@angular/core';
import {BreakpointObserver, Breakpoints} from '@angular/cdk/layout';
import {filter, mapTo, merge, Observable} from 'rxjs';
import {map, shareReplay} from 'rxjs/operators';
import {AuthService} from "../services/auth.service";
import {ResolveEnd, ResolveStart, Router} from "@angular/router";

@Component({
  selector: 'app-nav',
  templateUrl: './nav.component.html',
  styleUrls: ['./nav.component.scss']
})
export class NavComponent implements OnInit {
  public isHandset$: Observable<boolean> = this.breakpointObserver.observe(Breakpoints.Handset)
      .pipe(
          map(result => result.matches),
          shareReplay()
      );

  @ViewChild('item') public item: any;
  // public isLoading: boolean = true;
  public title = '';
  public isLoading$!: Observable<boolean>;
  private showLoaderEvents$!: Observable<boolean>;
  private hideLoaderEvents$!: Observable<boolean>;

  constructor(
      private breakpointObserver: BreakpointObserver,
      public authService: AuthService,
      private router: Router,
  ) {
  }

  public logout(): void {
    this.authService.logout();

    this.router.navigate(['/login']);
  }

  //
  // public ngAfterViewInit(): void {
  //   // if (this.item) {
  //   //   this.isLoading = false;
  //   // }
  // }

  public pageAdded($event: any) {
    this.title = $event.title;
  }

  ngOnInit(): void {
    this.showLoaderEvents$ = this.router.events.pipe(
        filter(e => e instanceof ResolveStart),
        mapTo(true),
    );

    this.hideLoaderEvents$ = this.router.events.pipe(
        filter(e => e instanceof ResolveEnd),
        mapTo(false),
    );

    this.isLoading$ = merge(this.hideLoaderEvents$, this.showLoaderEvents$);
  }
}
