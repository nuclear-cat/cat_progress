import {Component, OnDestroy, OnInit} from '@angular/core';
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {Subscription} from "rxjs";
import {HttpErrorResponse} from "@angular/common/http";
import {Router} from "@angular/router";
import {AuthService} from "../../services/auth.service";
import {NavService} from "../../services/nav-service";

@Component({
  selector: 'app-login-page',
  templateUrl: './login-page.component.html',
  styleUrls: ['./login-page.component.scss']
})
export class LoginPageComponent implements OnInit, OnDestroy {
  public loginForm!: FormGroup;
  public loginSubscription?: Subscription;
  public error: string | null = null;

  public constructor(
      private fb: FormBuilder,
      private authService: AuthService,
      private router: Router,
      public navService: NavService,
  ) {
  }

  public ngOnInit(): void {
    this.navService.title = 'Login';

    this.loginForm = this.fb.group({
      email: [null, [Validators.required, Validators.email]],
      password: [null, [Validators.required, Validators.minLength(1)]],
    });
  }

  public ngOnDestroy() {
    if (this.loginSubscription) {
      this.loginSubscription.unsubscribe();
    }
  }

  public submitForm(): void {
    this.loginForm.disable();
    this.error = null;

    if (this.loginForm.invalid) {
      return;
    }

    const email = this.loginForm.get('email')?.value;
    const password = this.loginForm.get('password')?.value;

    this.loginSubscription = this.authService.login(email, password).subscribe({
      complete: () => {
      },
      error: (err: HttpErrorResponse) => {
        this.error = err.error.message;
        this.loginForm.enable();
      },
      next: (next) => {
        return this.router.navigate(['/']);
      },
    });
  }
}
