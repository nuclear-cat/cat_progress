import {Component, OnInit, ViewChild} from '@angular/core';
import {BreakpointObserver, Breakpoints} from '@angular/cdk/layout';
import {filter, mapTo, merge, Observable} from 'rxjs';
import {map, shareReplay} from 'rxjs/operators';
import {AuthService} from "../services/auth.service";
import {ResolveEnd, ResolveStart, Router} from "@angular/router";
import {TemplatePortal} from "@angular/cdk/portal";
import {ApiService} from "../api/api.service";
import {ProfileService} from "../services/profile.service";

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
  public title = '';
  public isLoading$!: Observable<boolean>;
  private showLoaderEvents$!: Observable<boolean>;
    public portal$!: Observable<TemplatePortal>;
    private hideLoaderEvents$!: Observable<boolean>;

  constructor(
      private breakpointObserver: BreakpointObserver,
      public authService: AuthService,
      private router: Router,
      private apiService: ApiService,
      public profileService: ProfileService,
  ) {
  }

  public logout(): void {
    this.authService.logout();
    this.router.navigate(['/login']);
  }

    public ngOnInit(): void {
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
