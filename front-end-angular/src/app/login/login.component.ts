import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { first } from 'rxjs/operators';

import { AuthenticationService } from '@app/_services';

@Component({ templateUrl: 'login.component.html' })
export class LoginComponent implements OnInit {
    loginForm!: FormGroup;
    registerForm!: FormGroup;
    loading = false;
    loadingregister = false;
    submitted = false;
    submittedregister = false;
    error = '';
    errorregister = '';

    constructor(
        private formBuilder: FormBuilder,
        private route: ActivatedRoute,
        private router: Router,
        private authenticationService: AuthenticationService
    ) {
        // redirect to home if already logged in
        if (this.authenticationService.userValue) {
            this.router.navigate(['/']);
        }
    }

    ngOnInit() {
        this.loginForm = this.formBuilder.group({
            username: ['', Validators.required],
            password: ['', Validators.required]
        });
        this.registerForm = this.formBuilder.group({
            username: ['', Validators.required],
            password: ['', Validators.required],
            lastName: ['', Validators.required],
            firstName: ['', Validators.required]
        });
    }

    // convenience getter for easy access to form fields
    get f() { return this.loginForm.controls; }
    get fr() { return this.registerForm.controls; }

    onSubmit() {
        this.submitted = true;

        // stop here if form is invalid
        if (this.loginForm.invalid) {
            return;
        }

        this.error = '';
        this.loading = true;
        this.loginFn(this.f.username.value, this.f.password.value)

    }

    onSubmitRegister() {
        this.submittedregister = true;

        // stop here if form is invalid
        if (this.registerForm.invalid) {
            return;
        }

        this.errorregister = '';
        this.loadingregister = true;
        let data = {
            email: this.fr.username.value,
            password: this.fr.password.value,
            first_name: this.fr.firstName.value,
            last_name: this.fr.lastName.value
        }
        this.authenticationService.register(data)
            .pipe(first())
            .subscribe({
                next: () => {
                    this.f.username.setValue(this.fr.username.value);
                    this.f.password.setValue(this.fr.password.value);
                    this.loadingregister = false;
                    this.onSubmit()
                },
                error: error => {
                    this.errorregister = error;
                    this.loadingregister = false;
                }
            });
    }

    loginFn(username: string, password: string) {
        this.authenticationService.login(username, password)
            .pipe(first())
            .subscribe({
                next: () => {
                    this.getAccount()
                },
                error: error => {
                    this.error = error;
                    this.loading = false;
                }
            });
    }

    getAccount(){
        this.authenticationService.account()
            .pipe(first())
            .subscribe({
                next: () => {
                    // get return url from route parameters or default to '/'
                    const returnUrl = this.route.snapshot.queryParams['returnUrl'] || '/';
                    this.router.navigate([returnUrl]);
                },
                error: error => {
                    this.error = error;
                    this.loading = false;
                }
            });
    }
}
