<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { VContainer, VRow, VCol, VTextField, VCheckbox, VBtn, VAlert, VCard, VCardTitle, VCardText, VCardActions, VToolbar, VImg } from 'vuetify/components';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: (event) => {
            const response = event.detail.jetstream.response;
            if (response && response.status === 'success') {
                // Save token in local storage
                localStorage.setItem('token', response.token);

                // Redirect to appropriate page based on user role
                if (response.role === 'admin') {
                    Inertia.visit(route('admin.dashboard'));
                } else {
                    Inertia.visit(route('dashboard'));
                }
            } else {
                // Handle unsuccessful login
                console.error('Login unsuccessful');
            }
        },
    });
};
</script>

<template>
    <v-container class="d-flex justify-center align-center min-vh-100">
        <Head title="Log in" />

        <v-card max-width="500" width="100%" class="elevation-0 login-card">
            <!-- Logo -->
            <a href="/"> <v-img
                src="/Images/360_F_309742761_GPrVMq6nriNIAPgWJebFdg4dvO6MBIhI-removebg-preview.png"
                contain
                max-width="200"
                class="mx-auto mt-4"
            ></v-img></a>

            <v-card-text>
                <div v-if="status" class="mb-4">
                    <v-alert type="success" dismissible>
                        {{ status }}
                    </v-alert>
                </div>

                <form @submit.prevent="submit">
                    <v-row>
                        <v-col cols="12">
                            <v-text-field
                                label="Email"
                                v-model="form.email"
                                required
                                variant="underlined"
                                autofocus
                                autocomplete="username"
                                :error-messages="form.errors.email"
                                prepend-inner-icon="mdi-email"
                            ></v-text-field>
                        </v-col>
                    </v-row>

                    <v-row class="">
                        <v-col cols="12">
                            <v-text-field
                                label="Password"
                                type="password"
                                v-model="form.password"
                                required
                                variant="underlined"
                                autocomplete="current-password"
                                :error-messages="form.errors.password"
                                prepend-inner-icon="mdi-lock"
                            ></v-text-field>
                        </v-col>
                    </v-row>

                    <!-- <v-row class="">
                        <v-col cols="12">
                            <v-checkbox
                                label="Remember me"
                                v-model="form.remember"
                                class="remember-checkbox"
                            ></v-checkbox>
                        </v-col>
                    </v-row> -->

                    <v-card-actions class="d-flex justify-end mt-4">
                        <v-btn
                            color="white"
                            :loading="form.processing"
                            @click="submit"
                            width="100%"
                            class="login-button"
                            variant="tonal"
                            elevation="4"
                        >
                            Log in <v-icon>mdi-login</v-icon>
                        </v-btn>
                    </v-card-actions>

                    <v-row class="mt-2">
                        <v-col cols="12" class="d-flex justify-center">
                            <Link
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="forgot-password-link mr-4"
                            >
                                Forgot your password?
                            </Link>

                            <Link
                                :href="route('register')"
                                   class="forgot-password-link"
                            >
                                Don't have an account?
                            </Link>
                        </v-col>


                    </v-row>
                </form>
            </v-card-text>
        </v-card>
    </v-container>
</template>

<style>
.min-vh-100 {
    min-height: 100vh;
}

.login-card {
    border-radius: 5px;
}

.login-button {
    background-color:lightblue;
    color: white;
    font-weight: bold;
    text-transform: capitalize;
}

.forgot-password-link {
    color: gray;
    text-decoration: underline;
    font-size: 0.9rem;
}

.remember-checkbox .v-label {
    color: gray;
}

.v-card-title {
    justify-content: center;
    font-size: 1.5rem;
    font-weight: bold;
}
</style>
