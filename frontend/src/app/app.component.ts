import {Component, OnInit} from '@angular/core';
import {AuthService} from "./services/auth.service";
import {SwUpdate} from "@angular/service-worker";
import {MatIconRegistry} from "@angular/material/icon";
import {DomSanitizer} from "@angular/platform-browser";

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent implements OnInit {
  public isLoading: boolean = true;

  constructor(
      private authService: AuthService,
      private updates: SwUpdate,
      private matIconRegistry: MatIconRegistry,
      private domSanitizer: DomSanitizer
  ) {
    updates.versionUpdates.subscribe();

    this.matIconRegistry
        .addSvgIcon(
            'alternative',
            this.domSanitizer.bypassSecurityTrustResourceUrl('assets/mat-icons/alternative.svg'),
        )
        .addSvgIcon(
            'failed',
            this.domSanitizer.bypassSecurityTrustResourceUrl('assets/mat-icons/failed.svg'),
        )
        .addSvgIcon(
            'lazy',
            this.domSanitizer.bypassSecurityTrustResourceUrl('assets/mat-icons/lazy.svg'),
        )
        .addSvgIcon(
            'partially',
            this.domSanitizer.bypassSecurityTrustResourceUrl('assets/mat-icons/partially.svg'),
        );
  }

  ngOnInit(): void {
    const accessToken = localStorage.getItem('access-token');
    if (accessToken !== null) {
      this.authService.setAccessToken(accessToken);
    }

    const refreshToken = localStorage.getItem('refresh-token');
    if (refreshToken !== null) {
      this.authService.setRefreshToken(refreshToken);
    }
  }

  public onActivate($event: any) {
    this.isLoading = false;
  }
}
