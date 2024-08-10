<script>
import { ref, computed, watch } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import {
    VContainer,
    VRow,
    VCol,
    VTextField,
    VSelect,
    VBtn,
    VCard,
    VCardTitle,
    VCardText,
    VCardActions,
    VImg,
    VTextarea,
    VIcon,
} from 'vuetify/components';

export default {
    props: {
        categories: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            form: useForm({
                category_id: null,
                subject: '',
                subcategory_id: null,
                name: '',
                email: '',
                feedback: '',
            }),
            selectedCategory: null,
            rules: {
                required: value => !!value || 'Required.',
                email: value => {
                    const pattern = /^[^@]+@[^@]+\.[^@]+$/;
                    return pattern.test(value) || 'Invalid e-mail.';
                },
            },
        };
    },
    computed: {
        formattedCategories() {
            return this.categories.map(category => ({
                id: category.id,
                name: category.name,
            }));
        },
        filteredSubcategories() {
            const category = this.categories.find(cat => cat.id === this.form.category_id);
            return category ? category.subcategories.map(sub => ({
                id: sub.id,
                name: sub.name,
            })) : [];
        },
    },
    watch: {
        'form.category_id': function (newVal) {
            this.selectedCategory = newVal;
            this.form.subcategory_id = null; // Reset subcategory when category changes
        },
    },
    methods: {
        submitFeedback() {
            this.form.post(route('feedback.store'), {
                onFinish: response => {
                    if (response.props.flash.success) {
                        // Handle successful feedback submission
                        console.log('Feedback submitted successfully');
                    } else {
                        // Handle unsuccessful feedback submission
                        console.error('Feedback submission unsuccessful');
                    }
                },
            });
        },
    },
};
</script>

<template>
    <v-container class="d-flex justify-center align-center min-vh-100">
        <Head title="Give Feedback" />

        <v-card max-width="900" width="100%" class="elevation-0 feedback-card">
            <!-- Logo -->
            <a href="/">
                <v-img
                    src="/Images/LOGO_2.png"
                    contain
                    max-width="300"
                    class="mx-auto mt-4"
                ></v-img>
            </a>

            <v-card-text>
                <v-card-title
                    class="text-center"
                    style="background-color: darkblue; color: white"
                    >Give feedback</v-card-title
                >

                <br />

                <form @submit.prevent="submitFeedback">
                    <v-row>
                        <v-col>
                            <v-select
                                label="Category"
                                v-model="form.category_id"
                                :items="formattedCategories"
                                item-title="name"
                                item-value="id"
                                :rules="[rules.required]"
                                variant="outlined"
                                :error-messages="form.errors.category_id"
                                prepend-inner-icon="mdi-format-list-bulleted"
                            ></v-select>
                        </v-col>

                        <v-col>
                            <v-select
                                label="Subcategory"
                                v-model="form.subcategory_id"
                                :items="filteredSubcategories"
                                item-title="name"
                                item-value="id"
                                :rules="[rules.required]"
                                variant="outlined"
                                :error-messages="form.errors.subcategory_id"
                                prepend-inner-icon="mdi-format-list-bulleted"
                            ></v-select>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col>
                            <v-text-field
                                label="Email (optional)"
                                type="email"
                                v-model="form.email"
                                variant="outlined"
                                :rules="[rules.email]"
                                :error-messages="form.errors.email"
                                prepend-inner-icon="mdi-email"
                            ></v-text-field>
                        </v-col>

                        <v-col>
                            <v-text-field
                                label="Name (optional)"
                                v-model="form.name"
                                variant="outlined"
                                :error-messages="form.errors.name"
                                prepend-inner-icon="mdi-account"
                            ></v-text-field>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col>
                            <v-text-field
                                label="Subject"
                                v-model="form.subject"
                                :rules="[rules.required]"
                                variant="outlined"
                                :error-messages="form.errors.subject"
                                prepend-inner-icon="mdi-text-long"
                            ></v-text-field>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col cols="12">
                            <v-textarea
                                label="Feedback"
                                v-model="form.feedback"
                                :rules="[rules.required]"
                                variant="outlined"
                                :error-messages="form.errors.feedback"
                                prepend-inner-icon="mdi-message"
                                multiline
                                rows="5"
                            ></v-textarea>
                        </v-col>
                    </v-row>

                    <v-card-actions class="d-flex justify-end mt-4">
                        <v-btn
                            color="white"
                            :loading="form.processing"
                            @click="submitFeedback"
                            width="100%"
                            class="feedback-button"
                        >
                            Submit Feedback <v-icon>mdi-send</v-icon>
                        </v-btn>
                    </v-card-actions>
                </form>
                <p><strong>*</strong>All feedback provided will be treated with utmost confidentiality.</p>
            </v-card-text>
        </v-card>
    </v-container>
</template>

<style>
.min-vh-100 {
    min-height: 100vh;
}

.feedback-card {
    border-radius: 5px;
}

.feedback-button {
    background-color: darkblue;
    color: white;
    font-weight: bold;
    text-transform: capitalize;
}

.v-card-title {
    justify-content: center;
    font-size: 1.5rem;
    font-weight: bold;
}
</style>
