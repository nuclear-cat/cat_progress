import {ComponentFixture, TestBed} from '@angular/core/testing';

import {ProjectInviteConfirmPageComponent} from './project-invite-confirm-page.component';

describe('ProjectInviteConfirmPageComponent', () => {
    let component: ProjectInviteConfirmPageComponent;
    let fixture: ComponentFixture<ProjectInviteConfirmPageComponent>;

    beforeEach(async () => {
        await TestBed.configureTestingModule({
            declarations: [ProjectInviteConfirmPageComponent]
        })
            .compileComponents();
    });

    beforeEach(() => {
        fixture = TestBed.createComponent(ProjectInviteConfirmPageComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

    it('should create', () => {
        expect(component).toBeTruthy();
    });
});
