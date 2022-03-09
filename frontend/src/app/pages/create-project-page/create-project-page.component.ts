import {Component, OnInit} from '@angular/core';
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {ApiService} from "../../api/api.service";
import {Router} from "@angular/router";
import {ColorsResponse} from "../../api/response/colors-response";

@Component({
    selector: 'app-create-project-page',
    templateUrl: './create-project-page.component.html',
    styleUrls: ['./create-project-page.component.scss']
})
export class CreateProjectPageComponent implements OnInit {
    public form!: FormGroup;
    public title: string = 'Create project';
    public colors: string[] = [];
    public selectedColor!: string;

    public constructor(
        private apiService: ApiService,
        private formBuilder: FormBuilder,
        private router: Router,
    ) {
    }

    public ngOnInit(): void {

        this.apiService.getColors().subscribe({
            next: (next: ColorsResponse) => {
                this.colors = next.colors;
            }
        });

        this.form = this.formBuilder.group({
            title: [null, [Validators.required,]],
            description: [null, []],
            color: [null, []],
        });
    }

    public selectColor(color: string): void {
        this.selectedColor = color;
    }

    public submitForm() {
        this.apiService.createProject({
            title: this.form.get('title')?.value,
            description: this.form.get('description')?.value,
            color: this.selectedColor,
        }).subscribe({
            next: () => {
                this.router.navigate(['/projects']);
            }
        });
    }
}
