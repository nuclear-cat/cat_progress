import { ComponentFixture, TestBed } from '@angular/core/testing';

import { EditHabitPageComponent } from './edit-habit-page.component';

describe('EditHabitPageComponent', () => {
  let component: EditHabitPageComponent;
  let fixture: ComponentFixture<EditHabitPageComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ EditHabitPageComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(EditHabitPageComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
