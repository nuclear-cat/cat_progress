import {Component, OnInit} from '@angular/core';
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {ApiService} from "../../api/api.service";
import {HttpErrorResponse} from "@angular/common/http";
import {Router} from "@angular/router";

@Component({
  selector: 'app-registration-page',
  templateUrl: './registration-page.component.html',
  styleUrls: ['./registration-page.component.scss']
})
export class RegistrationPageComponent implements OnInit {

  public registrationForm!: FormGroup;
  public error: string | null = null;

  public constructor(
      private fb: FormBuilder,
      private apiService: ApiService,
      private router: Router,
  ) {
  }

  public ngOnInit(): void {
    this.registrationForm = this.fb.group({
      name: [null, [Validators.required,]],
      email: [null, [Validators.required, Validators.email]],
      password: [null, [Validators.required, Validators.minLength(1)]],
    });
  }

  public submitForm(): void {
    const name = this.registrationForm.get('name')?.value;
    const email = this.registrationForm.get('email')?.value;
    const password = this.registrationForm.get('password')?.value;
    const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;

    this.apiService.register({
      name: name,
      email: email,
      password: password,
      timezone: timezone,
    }).subscribe({
      complete: () => {
      },
      error: (err: HttpErrorResponse) => {
        this.error = err.error.message;
        this.registrationForm.enable();
      },
      next: (next) => {
        return this.router.navigate(['/registration-info']);
      },
    });
  }
}
