import {Component, OnInit} from '@angular/core';
import {AuthService} from "./services/auth.service";
import {SwUpdate} from "@angular/service-worker";

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent implements OnInit {
  constructor(private authService: AuthService, updates: SwUpdate) {
    updates.available.subscribe()
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
}
