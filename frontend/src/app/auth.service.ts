import {Injectable} from '@angular/core';
import {HttpClient} from "@angular/common/http";
import {catchError, Observable, throwError} from "rxjs";
import {tap} from "rxjs/operators";
import {ApiService} from "./api/api.service";
import {AuthResponse} from "./api/response/auth-response";


@Injectable({
  providedIn: 'root'
})
export class AuthService {

  private accessToken: string | null = null;
  private refreshToken: string | null = null;

  constructor(private httpClient: HttpClient, private apiService: ApiService,) {
  }

  public refresh(): Observable<AuthResponse> {
    return this.apiService.refresh(this.refreshToken as string)
        .pipe(
            tap((response: AuthResponse) => {
                  localStorage.setItem('access-token', response.access_token);
                  localStorage.setItem('refresh-token', response.refresh_token);

                  this.setAccessToken(response.access_token);
                  this.setRefreshToken(response.refresh_token);
                }
            ),
        );
  }

  public login(email: string, password: string): Observable<AuthResponse> {

    return this.apiService.login(email, password).pipe(tap((response) => {
      localStorage.setItem('access-token', response.access_token);
      localStorage.setItem('refresh-token', response.refresh_token);

      this.setAccessToken(response.access_token);
      this.setRefreshToken(response.refresh_token);
    }));
  }

  public setAccessToken(token: string | null): void {
    this.accessToken = token;
  }

  public getAccessToken(): string | null {
    return this.accessToken;
  }

  public setRefreshToken(token: string | null): void {
    this.refreshToken = token;
  }

  public getRefreshToken(): string | null {
    return this.refreshToken;
  }

  public isAuthenticated(): boolean {
    return !!this.accessToken;
  }

  public logout() {
    this.setAccessToken(null);
    this.setRefreshToken(null);
    localStorage.clear();
  }
}
